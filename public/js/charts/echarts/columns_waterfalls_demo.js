/* ------------------------------------------------------------------------------
 *
 *  # Echarts - columns and waterfalls
 *
 *  Columns and waterfalls chart configurations
 *
 *  Version: 1.0
 *  Latest update: August 1, 2015
 *
 * ---------------------------------------------------------------------------- */
var basic_columns;
$(function () {

    // Set paths
    // ------------------------------

    require.config({
        paths: {
            echarts: basepath + '/js/plugins/visualization/echarts'
        }
    });


    // Configuration
    // ------------------------------

    require(
            [
                'echarts',
                'echarts/theme/limitless',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            // Charts setup
                    function (ec, limitless) {


                        // Initialize charts
                        // ------------------------------
                        console.log(document.getElementById('basic_columns'));
                        if (document.getElementById('basic_columns') != null) {
                            console.log(document.getElementById('basic_columns'), "csa");
                            basic_columns = ec.init(document.getElementById('basic_columns'), limitless);
                           
                            //return false;
                            // Charts setup
                            // ------------------------------


                            //
                            // Basic columns options
                            //






                            basic_columns.setOption(basic_columns_options);

                            $('[data-toggle="tab"]').on('shown.bs.tab', function () {
                                if ($(this).attr('href') === "#report")
                                    basic_columns.resize();
                            });


                            // Resize charts
                            // ------------------------------

                            window.onresize = function () {
                                setTimeout(function () {
                                    basic_columns.resize();
                                }, 200);
                            }
                            var ecConfig = require('echarts/config');
                            basic_columns.on(ecConfig.EVENT.CLICK, eConsole);
                        }
                    }
            );
        });
