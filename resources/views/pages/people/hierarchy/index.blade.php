@extends('layouts.app-people')

@section('title', 'Hierarchy')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Hierarchy</h1>
                </div>
                <div class="col-sm-6 mt-2 mt-sm-0 text-right">
                    @if ($can_manage)
                        <a href="/people-hierarchy/manage" class="btn btn-primary">Manage</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <input type="text" id="searchBox" class="form-control" placeholder="Search employee...">
            <div id="tree" style="height:80vh;" class="w-full h-full bg-transparent"></div>
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.balkan.app/orgchart.css">
@endsection

@section('script')
    <script src="https://cdn.balkan.app/orgchart-community.js"></script>
    <script>
        OrgChart.templates.myIsla = Object.assign({}, OrgChart.templates.isla);
        // Wider + taller nodes to fit more text
        OrgChart.templates.myIsla.field_0 = '<text style="font-size:15px; font-weight:bold; fill:#2F4F4F; font-family:Arial;" x="120" y="72" text-anchor="middle">{val}</text>';
        OrgChart.templates.myIsla.field_1 = '<text style="font-size:13px;" fill="rgb(245, 124, 0)" x="120" y="92" text-anchor="middle">{val}</text>';
        OrgChart.templates.myIsla.size = [240, 140];
        OrgChart.templates.myIsla.node = `
            <rect x="0" y="20" width="240" height="120" rx="5" ry="5" fill="#f9f9f9" stroke="#aaa" stroke-width="1"></rect>
        `;
        // Make selection/search highlight match the bigger node box
        OrgChart.templates.myIsla.ripple = {
            radius: 130,
            color: "rgba(245, 124, 0, 0.25)",
            rect: { x: 0, y: 20, width: 240, height: 120 }
        };

        const nodes = @json($nodes ?? []);

        function truncateText(value, maxChars) {
            const s = (value ?? '').toString().trim();
            if (!s) return '';
            if (s.length <= maxChars) return s;
            return s.slice(0, Math.max(0, maxChars - 1)) + '…';
        }

        let lastHighlightedId = null;
        function highlightNodeById(id) {
            if (lastHighlightedId !== null) {
                const prevRect = document.querySelector(`[data-n-id="${lastHighlightedId}"] rect`);
                if (prevRect) {
                    prevRect.style.stroke = '';
                    prevRect.style.strokeWidth = '';
                }
            }

            const rect = document.querySelector(`[data-n-id="${id}"] rect`);
            if (rect) {
                rect.style.stroke = 'rgb(245, 124, 0)';
                rect.style.strokeWidth = '3';
                lastHighlightedId = id;
            } else {
                lastHighlightedId = null;
            }
        }

        // SVG <text> doesn't support CSS ellipsis reliably, so pre-truncate.
        // Keep full strings in tooltips for hover.
        nodes.forEach(n => {
            n.name_full = n.name ?? '';
            n.title_full = n.title ?? '';
            n.name_display = truncateText(n.name_full, 28);
            n.title_display = truncateText(n.title_full, 34);
        });

        var chart = new OrgChart(document.getElementById("tree"), {
            template: "myIsla",
            levelSeparation: 110,
            siblingSeparation: 70,
            subtreeSeparation: 90,
            nodeBinding: {
                field_0: "name_display",
                field_1: "title_display",
                img_0: "img"
            },
            nodes: nodes,
            nodeMouseOver: function (sender, args) {
                const node = args.node;
                const title = [node.name_full, node.title_full].filter(Boolean).join('\n');
                if (title) {
                    args.element.setAttribute('title', title);
                }
            },
            nodeMouseClick: OrgChart.action.none,
            onNodeClick: function (args) {
                // Keep our custom highlight in sync when user clicks a node
                highlightNodeById(args.node.id);
            }
        });

        document.getElementById("searchBox").addEventListener("keyup", function () {
        var value = this.value.toLowerCase();

            var found = nodes.find(n =>
                (n.name_full || '').toLowerCase().includes(value) ||
                ((n.title_full || '').toLowerCase().includes(value))
            );

            if (found) {
                chart.center(found.id);
                if (typeof chart.select === 'function') {
                    chart.select(found.id);
                } else if (typeof chart.setCursor === 'function') {
                    chart.setCursor(found.id);
                }
                // Make selected node clearly visible (orange outline)
                setTimeout(() => highlightNodeById(found.id), 0);
            }
        });
    </script>
@endsection