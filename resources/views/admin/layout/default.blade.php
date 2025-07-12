<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }}
    {{ Metronic::printClasses('html') }}>

<head>
    <meta charset="utf-8" />

    {{-- Title Section --}}
    <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

    {{-- Meta Data --}}
    <meta name="description" content="@yield('page_description', $page_description ?? '')" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="shortcut icon"
        href=" />

    {{-- Fonts --}}
    {{ Metronic::getGoogleFontsInclude() }}

    {{-- Global Theme Styles (used by all pages) --}}
    @foreach (config('layout.resources.css') as $style)
<link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}"
        rel="stylesheet" type="text/css" />
    @endforeach

    {{-- Layout Themes (used by all pages) --}}
    @foreach (Metronic::initThemes() as $theme)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}"
            rel="stylesheet" type="text/css" />
    @endforeach

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
    {{-- Includable CSS --}}
    @yield('styles')
    <script>
        var APP_URL = `{{ url('/') }}`;
        var CSRF_Token = `{{ csrf_token() }}`;
    </script>

</head>

<body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>

    @if (config('layout.page-loader.type') != '')
        @include('admin.layout.partials._page-loader')
    @endif

    @include('admin.layout.base._layout')
    <script>
        var HOST_URL = "";
    </script>
    {{-- Global Config (global config for global JS scripts) --}}
    <script>
        var KTAppSettings = `{!! json_encode(config('layout.js'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}`;
    </script>

    {{-- Global Theme JS Bundle (used by all pages)  --}}
    @foreach (config('layout.resources.js') as $script)
        <script src="{{ asset($script) }}" type="text/javascript"></script>
    @endforeach

    <script src="{{ asset('js/custom.js') }}" type="text/javascript"></script>
    {{-- Includable JS --}}
    @yield('scripts')

    <script type="text/javascript">
        $(function() {
            //Datemask dd/mm/yyyy
            $("#datemask").inputmask("dd/mm/yyyy", {
                "placeholder": "dd/mm/yyyy"
            });
            //Datemask2 mm/dd/yyyy
            $("#datemask2").inputmask("mm/dd/yyyy", {
                "placeholder": "mm/dd/yyyy"
            });
            //Money Euro
            $("[data-mask]").inputmask();
            //Date range picker
            $('#reservation').daterangepicker();
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            });
            //Date range as a button
            $('#fromtodate').daterangepicker({
                    autoUpdateInput: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Last 7 Days': [moment().subtract('days', 6), moment()],
                        'Last 30 Days': [moment().subtract('days', 29), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract(
                            'month', 1).endOf('month')]
                    },
                    // startDate: moment().subtract('days', 29),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'));
                }
            );

            $('#fromtodate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            });
            //Timepicker
            $(".timepicker").timepicker({
                showInputs: false
            });
        });
    </script>
    <script>
        function makeAllTablesSortable(selector = 'table.sortable-table') {
            document.querySelectorAll(selector).forEach((table, tableIndex) => {
                const headers = table.querySelectorAll('th');
                let currentSortIndex = table.dataset.defaultSortIndex ? parseInt(table.dataset.defaultSortIndex) :
                0;
                let sortAsc = table.dataset.defaultSortOrder === 'desc' ? false : true;

                headers.forEach((header, columnIndex) => {
                    header.classList.add('sortable');
                    header.style.cursor = 'pointer';

                    header.addEventListener('click', () => {
                        if (currentSortIndex === columnIndex) {
                            sortAsc = !sortAsc;
                        } else {
                            sortAsc = true;
                            currentSortIndex = columnIndex;
                        }

                        sortTable(table, columnIndex, sortAsc);

                        headers.forEach(h => h.classList.remove('asc', 'desc', 'active'));
                        header.classList.add(sortAsc ? 'asc' : 'desc', 'active');
                    });
                });

                // Apply default sort once DOM is ready (even if in hidden tab)
                sortTable(table, currentSortIndex, sortAsc);
                headers[currentSortIndex]?.classList.add(sortAsc ? 'asc' : 'desc', 'active');
            });
        }

        function sortTable(table, columnIndex, ascending) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const cellA = a.children[columnIndex]?.innerText.trim().toLowerCase();
                const cellB = b.children[columnIndex]?.innerText.trim().toLowerCase();

                const isNumeric = !isNaN(cellA) && !isNaN(cellB);
                if (isNumeric) {
                    return (parseFloat(cellA) - parseFloat(cellB)) * (ascending ? 1 : -1);
                }

                return cellA.localeCompare(cellB) * (ascending ? 1 : -1);
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            makeAllTablesSortable();
        });
    </script>


</body>

</html>
