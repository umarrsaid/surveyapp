var ChartsAmcharts = function() {
    var initChartdashboard_a = function() {
        var chart = AmCharts.makeChart("chart_a", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',

            "legend": {
                "useGraphSettings": true,
                "align": "center",
            },
        
            "dataProvider": [{
                "kab": "Januari",
                "spl": 235,
                "uji": 197,
                "kli": 50,
            }, {
                "kab": "Februari",
                "spl": 262,
                "uji": 185,
                "kli": 30,
            }, {
                "kab": "Maret",
                "spl": 309,
                "uji": 290,
                "kli": 100,
            }, {
                "kab": "April",
                "spl": 299,
                "uji": 250,
                "kli": 100,
            }, {
                "kab": "Mei",
                "spl": 380,
                "uji": 341,
                "kli": 120,
            }, {
                "kab": "Juni",
                "spl": 364,
                "uji": 255,
                "kli": 210,
            }, {
                "kab": "Juli",
                "spl": 283,
                "uji": 190,
                "kli": 110,
            }, {
                "kab": "Agustus",
                "spl": 260,
                "uji": 157,
                "kli": 160,
            }, {
                "kab": "September",
                "spl": 390,
                "uji": 267,
                "kli": 130,
            }, {
                "kab": "Oktober",
                "spl": 290,
                "uji": 237,
                "kli": 109,
            }, {
                "kab": "Nopember",
            }, {
                "kab": "Desember",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left",
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "title": "Jumlah Pengujian",
                "fillAlphas": 0.5,
                "lineColor": "#899bbb",
                "fillColors": "#899bbb",
                "valueField": "uji",
                "type": "column",
            },{
                "balloonText": "<span style='font-size:13px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "title": "Jumlah Sampel",
                "lineColor": "#d67e00",
                "fillColors": "#d67e00",
                "fillAlphas": 0.7,
                "valueField": "spl",
                "type": "column",
                "clustered":false,
                "columnWidth":0.5,
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "lineColor": "#2d2d2c",
                "title": "Jumlah Klien",
                "lineThickness": 3,
                "valueField": "kli",
                "dashLength": 3,
                "type":"smoothedLine"
            }],
            "chartCursor": {
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": true,
                "zoomable": false
            },
            "categoryField": "kab",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
                "labelRotation": 45
            }
        });

        $('#chart_a').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk = function() {
        var chart = AmCharts.makeChart("chart_ikk", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
            
            "legend": {
                "useGraphSettings": true,
                "align": "center",
            },
            "dataProvider": [{
                "kab": "Kabupaten Bogor",
                "ikk1": 35,
                "ikk2": 19,
                "ikk3": 30,
                "ikk4": 40,
                "ikk5": 20,
            }, {
                "kab": "Kabupaten Sukabumi",
                "ikk1": 22,
                "ikk2": 85,
                "ikk3": 30,
                "ikk4": 40,
                "ikk5": 20,
            }, {
                "kab": "Kabupaten Cianjur",
                "ikk1": 39,
                "ikk2": 29,
                "ikk3": 50,
                "ikk4": 30,
                "ikk5": 12,
            }, {
                "kab": "Kabupaten Bandung",
                "ikk1": 99,
                "ikk2": 50,
                "ikk3": 30,
                "ikk4": 34,
                "ikk5": 65,
            }, {
                "kab": "Kabupaten Garut",
                "ikk1": 38,
                "ikk2": 41,
                "ikk3": 44,
                "ikk4": 32,
                "ikk5": 25,
            }, {
                "kab": "Kabupaten Tasikmalaya",
                "ikk1": 64,
                "ikk2": 55,
                "ikk3": 56,
                "ikk4": 30,
                "ikk5": 33,
            }, {
                "kab": "Kabupaten Ciamis",
                "ikk1": 83,
                "ikk2": 90,
                "ikk3": 33,
                "ikk4": 40,
                "ikk5": 45,
            }, {
                "kab": "Kabupaten Kuningan",
                "ikk1": 60,
                "ikk2": 57,
                "ikk3": 65,
                "ikk4": 33,
                "ikk5": 56,
            }, {
                "kab": "Kabupaten Cirebon",
                "ikk1": 65,
                "ikk2": 67,
                "ikk3": 43,
                "ikk4": 54,
                "ikk5": 35,
            }, {
                "kab": "Kabupaten Majalengka",
                "ikk1": 90,
                "ikk2": 67,
                "ikk3": 22,
                "ikk4": 48,
                "ikk5": 64,
            }, {
                "kab": "Kabupaten Sumedang",
                "ikk1": 70,
                "ikk2": 67,
                "ikk3": 34,
                "ikk4": 43,
                "ikk5": 33,
            }, {
                "kab": "Kabupaten Indramayu",
                "ikk1": 90,
                "ikk2": 27,
                "ikk3": 97,
                "ikk4": 35,
                "ikk5": 21,
            }, {
                "kab": "Kabupaten Subang",
                "ikk1": 39,
                "ikk2": 27,
                "ikk3": 65,
                "ikk4": 18,
                "ikk5": 0,
            }, {
                "kab": "Kabupaten Karawang",
                "ikk1": 30,
                "ikk2": 73,
                "ikk3": 56,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kabupaten Bekasi",
                "ikk1": 35,
                "ikk2": 53,
                "ikk3": 54,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kabupaten Bandung Barat",
                "ikk1": 66,
                "ikk2": 67,
                "ikk3": 54,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kabupaten Pangandaran",
                "ikk1": 89,
                "ikk2": 26,
                "ikk3": 34,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Bogor",
                "ikk1": 56,
                "ikk2": 45,
                "ikk3": 56,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Sukabumi",
                "ikk1": 56,
                "ikk2": 54,
                "ikk3": 65,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Bandung",
                "ikk1": 83,
                "ikk2": 56,
                "ikk3": 44,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Cirebon",
                "ikk1": 73,
                "ikk2": 87,
                "ikk3": 12,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Bekasi",
                "ikk1": 34,
                "ikk2": 43,
                "ikk3": 25,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Depok",
                "ikk1": 56,
                "ikk2": 66,
                "ikk3": 33,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Cimahi",
                "ikk1": 77,
                "ikk2": 45,
                "ikk3": 48,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Tasikmalaya",
                "ikk1": 98,
                "ikk2": 55,
                "ikk3": 44,
                "ikk4": 0,
                "ikk5": 0,
            }, {
                "kab": "Kota Banjar",
                "ikk1": 66,
                "ikk2": 34,
                "ikk3": 62,
                "ikk4": 0,
                "ikk5": 0,
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "title": "Landasan legalitas dan keutuhan keluarga",
                "fillAlphas": 0.5,
                "lineColor": "#bbbbbb",
                "fillColors": "#bbbbbb",
                "valueField": "ikk1",
                "type": "column"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "bubble",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "lineColor": "#4384d4",
                "title": "Ketahanan Fisik",
                "lineThickness": 2,
                "valueField": "ikk2",
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "title": "Ketahanan Ekonomi",
                "lineThickness": 2,
                "valueField": "ikk3",
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "none",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "lineColor": "#333333",
                "title": "Ketahanan Sosial-Psikologi",
                "lineThickness": 3,
                "valueField": "ikk4",
                "dashLength": 3,
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "none",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "lineColor": "#999999",
                "title": "Ketahanan Sosial-Budaya",
                "lineThickness": 3,
                "valueField": "ikk5",
                "dashLength": 3,
                "type":"smoothedLine"
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": true,
                "zoomable": false
            },
            "categoryField": "kab",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
                "labelRotation": 45
            }
        });

        $('#chart_ikk').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk1 = function() {
        var chart = AmCharts.makeChart("chart_ikk1", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikk1": 23,
            }, {
                "bln": "Feb",
                "ikk1": 26,
            }, {
                "bln": "Mar",
                "ikk1": 30,
            }, {
                "bln": "Apr",
                "ikk1": 29,
            }, {
                "bln": "Mei",
                "ikk1": 30,
            }, {
                "bln": "Jun",
                "ikk1": 34,
            }, {
                "bln": "Jul",
                "ikk1": 23,
            }, {
                "bln": "Agu",
                "ikk1": 26,
            }, {
                "bln": "Sep",
                "ikk1": 30,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Landasan legalitas dan keutuhan keluarga",
                "fillAlphas": 0.5,
                "valueField": "ikk1",
                "labelText": "[[ikk1]]",
                "labelPosition": "inside",
                "fillColors":"#bbbbbb",
                "lineColor": "#bbbbbb",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikk1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk2 = function() {
        var chart = AmCharts.makeChart("chart_ikk2", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikk2": 18,
            }, {
                "bln": "Feb",
                "ikk2": 16,
            }, {
                "bln": "Mar",
                "ikk2": 11,
            }, {
                "bln": "Apr",
                "ikk2": 8,
            }, {
                "bln": "Mei",
                "ikk2": 15,
            }, {
                "bln": "Jun",
                "ikk2": 19,
            }, {
                "bln": "Jul",
                "ikk2": 10,
            }, {
                "bln": "Agu",
                "ikk2": 8,
            }, {
                "bln": "Sep",
                "ikk2": 11,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Fisik",
                "fillAlphas": 0.5,
                "valueField": "ikk2",
                "labelText": "[[ikk2]]",
                "labelPosition": "inside",
                "fillColors":"#4384d4",
                "lineColor": "#4384d4",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikk2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk3 = function() {
        var chart = AmCharts.makeChart("chart_ikk3", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikk3": 20,
            }, {
                "bln": "Feb",
                "ikk3": 23,
            }, {
                "bln": "Mar",
                "ikk3": 19,
            }, {
                "bln": "Apr",
                "ikk3": 28,
            }, {
                "bln": "Mei",
                "ikk3": 33,
            }, {
                "bln": "Jun",
                "ikk3": 27,
            }, {
                "bln": "Jul",
                "ikk3": 27,
            }, {
                "bln": "Agu",
                "ikk3": 23,
            }, {
                "bln": "Sep",
                "ikk3": 19,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Ekonomi",
                "fillAlphas": 0.5,
                "valueField": "ikk3",
                "labelText": "[[ikk3]]",
                "labelPosition": "inside",
                "fillColors":"#84b761",
                "lineColor": "#84b761",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikk3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk4 = function() {
        var chart = AmCharts.makeChart("chart_ikk4", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikk4": 35,
            }, {
                "bln": "Feb",
                "ikk4": 32,
            }, {
                "bln": "Mar",
                "ikk4": 25,
            }, {
                "bln": "Apr",
                "ikk4": 24,
            }, {
                "bln": "Mei",
                "ikk4": 42,
            }, {
                "bln": "Jun",
                "ikk4": 38,
            }, {
                "bln": "Jul",
                "ikk4": 44,
            }, {
                "ikk4": 32,
            }, {
                "bln": "Sep",
                "ikk4": 25,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Sosial-Psikologi",
                "fillAlphas": 0.5,
                "valueField": "ikk4",
                "labelText": "[[ikk4]]",
                "labelPosition": "inside",
                "fillColors":"#333333",
                "lineColor": "#333333",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikk4').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikk5 = function() {
        var chart = AmCharts.makeChart("chart_ikk5", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikk5": 29,
            }, {
                "bln": "Feb",
                "ikk5": 22
            }, {
                "bln": "Mar",
                "ikk5": 23
            }, {
                "bln": "Apr",
                "ikk5": 25
            }, {
                "bln": "Mei",
                "ikk5": 27,
            }, {
                "bln": "Jun",
                "ikk5": 29
            }, {
                "bln": "Jul",
                "ikk5": 18
            }, {
                "bln": "Agu",
                "ikk5": 22
            }, {
                "bln": "Sep",
                "ikk5": 23
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Sosial-Budaya",
                "fillAlphas": 0.5,
                "valueField": "ikk5",
                "labelText": "[[ikk5]]",
                "labelPosition": "inside",
                "fillColors":"#999999",
                "lineColor": "#999999",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikk5').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka = function() {
        var chart = AmCharts.makeChart("chart_ikka", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',
            "color":    '#888888',

            "legend": {
                "useGraphSettings": true,
                "align": "center",
            },
            "dataProvider": [{
                "kab": "Kabupaten Bogor",
                "ikka1": 80,
                "ikka2": 97,
                "ikka3": 35,
                "ikka4": 44,
                "ikka5": 12,
            }, {
                "kab": "Kabupaten Sukabumi",
                "ikka1": 62,
                "ikka2": 85,
                "ikka3": 40,
                "ikka4": 20,
                "ikka5": 20,
            }, {
                "kab": "Kabupaten Cianjur",
                "ikka1": 39,
                "ikka2": 20,
                "ikka3": 10,
                "ikka4": 32,
                "ikka5": 53,
            }, {
                "kab": "Kabupaten Bandung",
                "ikka1": 29,
                "ikka2": 25,
                "ikka3": 44,
                "ikka4": 34,
                "ikka5": 33,
            }, {
                "kab": "Kabupaten Garut",
                "ikka1": 80,
                "ikka2": 41,
                "ikka3": 64,
                "ikka4": 4,
                "ikka5": 33,
            }, {
                "kab": "Kabupaten Tasikmalaya",
                "ikka1": 64,
                "ikka2": 25,
                "ikka3": 43,
                "ikka4": 23,
                "ikka5": 13,
            }, {
                "kab": "Kabupaten Ciamis",
                "ikka1": 23,
                "ikka2": 19,
                "ikka3": 52,
                "ikka4": 23,
                "ikka5": 44,
            }, {
                "kab": "Kabupaten Kuningan",
                "ikka1": 60,
                "ikka2": 57,
                "ikka3": 43,
                "ikka4": 32,
                "ikka5": 44,
            }, {
                "kab": "Kabupaten Cirebon",
                "ikka1": 40,
                "ikka2": 67,
                "ikka3": 34,
                "ikka4": 35,
                "ikka5": 23,
            }, {
                "kab": "Kabupaten Majalengka",
                "ikka1": 39,
                "ikka2": 43,
                "ikka3": 34,
                "ikka4": 55,
                "ikka5": 23,
            }, {
                "kab": "Kabupaten Sumedang",
                "ikka1": 30,
                "ikka2": 26,
                "ikka3": 32,
                "ikka4": 23,
                "ikka5": 23,
            }, {
                "kab": "Kabupaten Indramayu",
                "ikka1": 45,
                "ikka2": 67,
                "ikka3": 43,
                "ikka4": 33,
                "ikka5": 22,
            }, {
                "kab": "Kabupaten Subang",
                "ikka1": 30,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kabupaten Karawang",
                "ikka1": 87,
                "ikka2": 67,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kabupaten Bekasi",
                "ikka1": 56,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kabupaten Bandung Barat",
                "ikka1": 30,
                "ikka2": 67,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kabupaten Pangandaran",
                "ikka1": 76,
                "ikka2": 26,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Bogor",
                "ikka1": 56,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Sukabumi",
                "ikka1": 90,
                "ikka2": 67,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Bandung",
                "ikka1": 39,
                "ikka2": 67,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Cirebon",
                "ikka1": 30,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Bekasi",
                "ikka1": 30,
                "ikka2": 26,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Depok",
                "ikka1": 30,
                "ikka2": 67,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Cimahi",
                "ikka1": 77,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Tasikmalaya",
                "ikka1": 39,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }, {
                "kab": "Kota Banjar",
                "ikka1": 30,
                "ikka2": 27,
                "ikka3": 0,
                "ikka4": 0,
                "ikka5": 0,
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "title": "Hidup",
                "fillAlphas": 0.35,
                "valueField": "ikka1",
                "type": "column"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "bubble",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "lineColor": "#4384d4",
                "lineThickness": 2,
                "title": "Perlindungan",
                "valueField": "ikka2",
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "useLineColorForBulletBorder": true,
                "bulletColor": "#FFFFFF",
                "lineThickness": 2,
                "title": "Tumbuh Kembang",
                "valueField": "ikka3",
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "lineColor": "#333333",
                "title": "Partisipasi",
                "lineThickness": 3,
                "valueField": "ikka4",
                "dashLength": 3,
                "type":"smoothedLine"
            }, {
                "balloonText": "<span style='font-size:10px;'>[[title]] :<b>[[value]]</b> [[additional]]</span>",
                "lineColor": "#999999",
                "title": "Identitas",
                "lineThickness": 3,
                "valueField": "ikka5",
                "dashLength": 3,
                "type":"smoothedLine"
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": true,
                "zoomable": false
            },
            "categoryField": "kab",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
                "labelRotation": 45
            }
        });

        $('#chart_ikka').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka1 = function() {
        var chart = AmCharts.makeChart("chart_ikka1", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikka1": 23,
            }, {
                "bln": "Feb",
                "ikka1": 26,
            }, {
                "bln": "Mar",
                "ikka1": 30,
            }, {
                "bln": "Apr",
                "ikka1": 29,
            }, {
                "bln": "Mei",
                "ikka1": 30,
            }, {
                "bln": "Jun",
                "ikka1": 34,
            }, {
                "bln": "Jul",
                "ikka1": 23,
            }, {
                "bln": "Agu",
                "ikka1": 26,
            }, {
                "bln": "Sep",
                "ikka1": 30,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Landasan legalitas dan keutuhan keluarga",
                "fillAlphas": 0.5,
                "valueField": "ikka1",
                "labelText": "[[ikka1]]",
                "labelPosition": "inside",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikka1').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka2 = function() {
        var chart = AmCharts.makeChart("chart_ikka2", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikka2": 18,
            }, {
                "bln": "Feb",
                "ikka2": 16,
            }, {
                "bln": "Mar",
                "ikka2": 11,
            }, {
                "bln": "Apr",
                "ikka2": 8,
            }, {
                "bln": "Mei",
                "ikka2": 15,
            }, {
                "bln": "Jun",
                "ikka2": 19,
            }, {
                "bln": "Jul",
                "ikka2": 10,
            }, {
                "bln": "Agu",
                "ikka2": 8,
            }, {
                "bln": "Sep",
                "ikka2": 11,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Fisik",
                "fillAlphas": 0.5,
                "valueField": "ikka2",
                "labelText": "[[ikka2]]",
                "labelPosition": "inside",
                "fillColors":"#4384d4",
                "lineColor": "#4384d4",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikka2').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka3 = function() {
        var chart = AmCharts.makeChart("chart_ikka3", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikka3": 20,
            }, {
                "bln": "Feb",
                "ikka3": 23,
            }, {
                "bln": "Mar",
                "ikka3": 19,
            }, {
                "bln": "Apr",
                "ikka3": 28,
            }, {
                "bln": "Mei",
                "ikka3": 33,
            }, {
                "bln": "Jun",
                "ikka3": 27,
            }, {
                "bln": "Jul",
                "ikka3": 27,
            }, {
                "bln": "Agu",
                "ikka3": 23,
            }, {
                "bln": "Sep",
                "ikka3": 19,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Ekonomi",
                "fillAlphas": 0.5,
                "valueField": "ikka3",
                "labelText": "[[ikka3]]",
                "labelPosition": "inside",
                "fillColors":"#84b761",
                "lineColor": "#84b761",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikka3').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka4 = function() {
        var chart = AmCharts.makeChart("chart_ikka4", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikka4": 35,
            }, {
                "bln": "Feb",
                "ikka4": 32,
            }, {
                "bln": "Mar",
                "ikka4": 25,
            }, {
                "bln": "Apr",
                "ikka4": 24,
            }, {
                "bln": "Mei",
                "ikka4": 42,
            }, {
                "bln": "Jun",
                "ikka4": 38,
            }, {
                "bln": "Jul",
                "ikka4": 44,
            }, {
                "ikka4": 32,
            }, {
                "bln": "Sep",
                "ikka4": 25,
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Sosial-Psikologi",
                "fillAlphas": 0.5,
                "valueField": "ikka4",
                "labelText": "[[ikka4]]",
                "labelPosition": "inside",
                "fillColors":"#333333",
                "lineColor": "#333333",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikka4').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    var initChartdashboardikka5 = function() {
        var chart = AmCharts.makeChart("chart_ikka5", {
            "type": "serial",
            "theme": "light",
            "fontFamily": 'Open Sans',            
            "color":    '#888',
        
            "dataProvider": [{
                "bln": "Jan",
                "ikka5": 29,
            }, {
                "bln": "Feb",
                "ikka5": 22
            }, {
                "bln": "Mar",
                "ikka5": 23
            }, {
                "bln": "Apr",
                "ikka5": 25
            }, {
                "bln": "Mei",
                "ikka5": 27,
            }, {
                "bln": "Jun",
                "ikka5": 29
            }, {
                "bln": "Jul",
                "ikka5": 18
            }, {
                "bln": "Agu",
                "ikka5": 22
            }, {
                "bln": "Sep",
                "ikka5": 23
            }, {
                "bln": "Okt",
            }, {
                "bln": "Nov",
            }, {
                "bln": "Des",
            }],
            "valueAxes": [{
                "axisAlpha": 0,
                "minimum": 0,
                "position": "left"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] bulan [[category]]:<b>[[value]]</b> [[additional]]</span>",
                "title": "Ketahanan Sosial-Budaya",
                "fillAlphas": 0.5,
                "valueField": "ikka5",
                "labelText": "[[ikka5]]",
                "labelPosition": "inside",
                "fillColors":"#999999",
                "lineColor": "#999999",
                "type": "column",
            }],
            "chartCursor": {
                "categoryBalloonblnFormat": "DD",
                "cursorAlpha": 0.1,
                "cursorColor": "#000000",
                "fullWidth": true,
                "valueBalloonsEnabled": false,
                "zoomable": false
            },
            "categoryField": "bln",
            "categoryAxis": {
                "axisColor": "#555555",
                "gridAlpha": 0.1,
                "gridColor": "#FFFFFF",
            }
        });

        $('#chart_ikka5').closest('.portlet').find('.fullscreen').click(function() {
            chart.invaliblnSize();
        });
    }

    return {
        init: function() {
            initChartdashboard_a();
            initChartdashboardikk();
            initChartdashboardikk1();
            initChartdashboardikk2();
            initChartdashboardikk3();
            initChartdashboardikk4();
            initChartdashboardikk5();
            initChartdashboardikka();
            initChartdashboardikka1();
            initChartdashboardikka2();
            initChartdashboardikka3();
            initChartdashboardikka4();
            initChartdashboardikka5();
        }
    };
}();

jQuery(document).ready(function() {    
   ChartsAmcharts.init(); 
});