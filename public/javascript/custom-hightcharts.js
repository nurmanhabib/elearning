
$(function () {
    var names = $('#hasil-nama-contributor').val();
    var nameArray = new Array();
    nameArray = names.split(",");
    var removelastname = nameArray.slice(0, -1);
    
    var skor = $('#hasil-skor-artikel').val();
    var skorArray = new Array();
    skorArray = skor.split(",");
    var removelasttskor = skorArray.slice(0, -1);

    var skortointeger = removelasttskor.map(parseFloat);
    
   
    $('.top-submit-artikel').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 10 Submit Artikel Terbanyak'
        },
        subtitle: {
            text: 'Desa Membangun'
        },
        xAxis: {
            categories: removelastname,
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Jumlah Artikel '
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Jumlah Artikel',
            data: skortointeger

        }]
    });
    

    var namakelasonline = $('#hasil-nama-kelas-online').val();
    var nameKelasOnlineArray = new Array();
    nameKelasOnlineArray = namakelasonline.split(",");
    var removelastnamekelasonline = nameKelasOnlineArray.slice(0, -1);
    
    var skorkelasonline = $('#hasil-skor-kelas-online').val();
    var skorKelasOnlineArray = new Array();
    skorKelasOnlineArray = skorkelasonline.split(",");
    var removelasttskorkelasonline = skorKelasOnlineArray.slice(0, -1);

    var skorkelasonlinetointeger = removelasttskorkelasonline.map(parseFloat);


    $('.top-aktif-kelas').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 10 Aktif DiKelas'
        },
        subtitle: {
            text: 'Desa Membangun'
        },
        xAxis: {
            categories: removelastnamekelasonline,
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Jumlah Kelas'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Jumlah Kelas Di Ikuti ',
            data: skorkelasonlinetointeger

        }]
    });
});