$(function () {

	if (!$('#area-chart').length) { return false; }
	
	area ();	

	$(window).resize (target_admin.debounce (area, 250));

});

function area () {
	$('#area-chart').empty ();

	Morris.Area ({
		element: 'area-chart',
		data: [
			{period: '2015-01', vendas: 2666, lucro: null, pontos: 2647},
			{period: '2015-02', vendas: 2778, lucro: 2294, pontos: 2441},
			{period: '2015-03', vendas: 4912, lucro: 1969, pontos: 2501},
			{period: '2015-04', vendas: 3767, lucro: 3597, pontos: 5689},
			{period: '2015-05', vendas: 6810, lucro: 1914, pontos: 2293},
			{period: '2015-06', vendas: 5670, lucro: 4293, pontos: 1881},
			{period: '2015-07', vendas: 4820, lucro: 3795, pontos: 1588},
			{period: '2015-08', vendas: 15073, lucro: 5967, pontos: 5175},
			{period: '2015-09', vendas: 10687, lucro: 4460, pontos: 2028},
			{period: '2015-10', vendas: 8432, lucro: 5713, pontos: 1791},
			{period: '2015-11', vendas: 5670, lucro: 4293, pontos: 1881},
			{period: '2015-12', vendas: 10687, lucro: 4460, pontos: 2028}
		],
		xkey: 'period',
		xLabels: 'month',
		ykeys: ['vendas', 'lucro', 'pontos'],
		labels: ['Vendas', 'Lucro', 'Pontos'],
		pointSize: 3,
		hideHover: 'auto',
		lineColors: target_admin.layoutColors
	});
}