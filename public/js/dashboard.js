(function ($) {
  'use strict';

  if ($("#visit-sale-chart").length) {
    const ctx = document.getElementById('visit-sale-chart').getContext('2d');

    var graphGradient1 = document.getElementById('visit-sale-chart').getContext("2d");
    var graphGradient2 = document.getElementById('visit-sale-chart').getContext("2d");
    var graphGradient3 = document.getElementById('visit-sale-chart').getContext("2d");

    var gradientStrokeViolet = graphGradient1.createLinearGradient(0, 0, 0, 181);
    gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
    gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
    var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';

    var gradientStrokeBlue = graphGradient2.createLinearGradient(0, 0, 0, 360);
    gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
    gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
    var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

    var gradientStrokeRed = graphGradient3.createLinearGradient(0, 0, 0, 300);
    gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
    gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
    var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

    const bgColor1 = "rgba(218, 140, 255, 1)";
    const bgColor2 = "rgba(54, 215, 232, 1)";
    const bgColor3 = "rgba(255, 191, 150, 1)";

    const bulanLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    const bukuPerBulanData = (window.dashboardData && window.dashboardData.bukuPerBulan)
      ? window.dashboardData.bukuPerBulan
      : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: bulanLabels,
        datasets: [{
          label: "Jumlah Buku",
          borderColor: gradientStrokeViolet,
          backgroundColor: gradientStrokeViolet,
          fillColor: bgColor1,
          hoverBackgroundColor: gradientStrokeViolet,
          pointRadius: 0,
          fill: false,
          borderWidth: 1,
          data: bukuPerBulanData,
          barPercentage: 0.5,
          categoryPercentage: 0.5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        elements: {
          line: {
            tension: 0.4,
          },
        },
        scales: {
          y: {
            display: false,
            grid: {
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
            },
          },
          x: {
            display: true,
            grid: {
              display: false,
            },
          }
        },
        plugins: {
          legend: {
            display: false,
          }
        }
      },
      plugins: [{
        afterUpdate: function (chart) {
          const chartId = chart.canvas.id;
          const legendId = `${chartId}-legend`;
          const legendEl = document.getElementById(legendId);

          if (!legendEl) return;
          legendEl.innerHTML = "";

          const ul = document.createElement('ul');
          for (let i = 0; i < chart.data.datasets.length; i++) {
            ul.innerHTML += `
              <li>
                <span style="background-color: ${chart.data.datasets[i].fillColor}"></span>
                ${chart.data.datasets[i].label}
              </li>
            `;
          }
          legendEl.appendChild(ul);
        }
      }]
    });
  }

  if ($("#traffic-chart").length) {
    const ctx = document.getElementById('traffic-chart').getContext('2d');

    var graphGradient1 = document.getElementById("traffic-chart").getContext('2d');
    var graphGradient2 = document.getElementById("traffic-chart").getContext('2d');
    var graphGradient3 = document.getElementById("traffic-chart").getContext('2d');
    var graphGradient4 = document.getElementById("traffic-chart").getContext('2d');
    var graphGradient5 = document.getElementById("traffic-chart").getContext('2d');

    var gradientStrokeBlue = graphGradient1.createLinearGradient(0, 0, 0, 181);
    gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
    gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
    var gradientLegendBlue = 'rgba(54, 215, 232, 1)';

    var gradientStrokeRed = graphGradient2.createLinearGradient(0, 0, 0, 50);
    gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
    gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
    var gradientLegendRed = 'rgba(254, 112, 150, 1)';

    var gradientStrokeGreen = graphGradient3.createLinearGradient(0, 0, 0, 300);
    gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
    gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
    var gradientLegendGreen = 'rgba(6, 185, 157, 1)';

    var gradientStrokeYellow = graphGradient4.createLinearGradient(0, 0, 0, 300);
    gradientStrokeYellow.addColorStop(0, 'rgba(255, 206, 86, 1)');
    gradientStrokeYellow.addColorStop(1, 'rgba(255, 159, 64, 1)');
    var gradientLegendYellow = 'rgba(255, 159, 64, 1)';

    var gradientStrokePurple = graphGradient5.createLinearGradient(0, 0, 0, 300);
    gradientStrokePurple.addColorStop(0, 'rgba(153, 102, 255, 1)');
    gradientStrokePurple.addColorStop(1, 'rgba(201, 203, 207, 1)');
    var gradientLegendPurple = 'rgba(153, 102, 255, 1)';

    const kategoriLabels = (window.dashboardData && window.dashboardData.kategoriLabels)
      ? window.dashboardData.kategoriLabels
      : ['Belum Ada Data'];

    const kategoriTotals = (window.dashboardData && window.dashboardData.kategoriTotals)
      ? window.dashboardData.kategoriTotals
      : [1];

    const chartColors = [
      gradientStrokeBlue,
      gradientStrokeGreen,
      gradientStrokeRed,
      gradientStrokeYellow,
      gradientStrokePurple
    ];

    const legendColors = [
      gradientLegendBlue,
      gradientLegendGreen,
      gradientLegendRed,
      gradientLegendYellow,
      gradientLegendPurple
    ];

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: kategoriLabels,
        datasets: [{
          data: kategoriTotals,
          backgroundColor: kategoriTotals.map((_, index) => chartColors[index % chartColors.length]),
          hoverBackgroundColor: kategoriTotals.map((_, index) => chartColors[index % chartColors.length]),
          borderColor: kategoriTotals.map((_, index) => chartColors[index % chartColors.length]),
          legendColor: kategoriTotals.map((_, index) => legendColors[index % legendColors.length])
        }]
      },
      options: {
        cutout: 50,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: true,
        showScale: true,
        legend: false,
        plugins: {
          legend: {
            display: false,
          }
        }
      },
      plugins: [{
        afterUpdate: function (chart) {
          const chartId = chart.canvas.id;
          const legendId = `${chartId}-legend`;
          const legendEl = document.getElementById(legendId);

          if (!legendEl) return;
          legendEl.innerHTML = "";

          const ul = document.createElement('ul');
          for (let i = 0; i < chart.data.datasets[0].data.length; i++) {
            ul.innerHTML += `
                <li>
                  <span style="background-color: ${chart.data.datasets[0].legendColor[i]}"></span>
                  ${chart.data.labels[i]}
                </li>
              `;
          }
          legendEl.appendChild(ul);
        }
      }]
    });
  }

  if ($("#sales-7days-chart").length) {
    const ctx = document.getElementById('sales-7days-chart').getContext('2d');

    var graphGradient = document.getElementById('sales-7days-chart').getContext("2d");
    var gradientStrokeSales = graphGradient.createLinearGradient(0, 0, 0, 180);
    gradientStrokeSales.addColorStop(0, 'rgba(54, 215, 232, 1)');
    gradientStrokeSales.addColorStop(1, 'rgba(177, 148, 250, 1)');

    const penjualan7HariLabels = (window.dashboardData && window.dashboardData.penjualan7HariLabels)
      ? window.dashboardData.penjualan7HariLabels
      : [];

    const penjualan7HariTotals = (window.dashboardData && window.dashboardData.penjualan7HariTotals)
      ? window.dashboardData.penjualan7HariTotals
      : [];

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: penjualan7HariLabels,
        datasets: [{
          label: 'Omzet',
          data: penjualan7HariTotals,
          borderColor: gradientStrokeSales,
          backgroundColor: 'rgba(177, 148, 250, 0.12)',
          pointBackgroundColor: 'rgba(54, 215, 232, 1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(54, 215, 232, 1)',
          pointRadius: 3,
          pointHoverRadius: 4,
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        plugins: {
          legend: {
            display: false
          }
        },
        elements: {
          line: {
            tension: 0.3
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              maxTicksLimit: 4,
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            },
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              maxRotation: 0,
              autoSkip: true
            }
          }
        }
      }
    });
  }

  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }

  if ($.cookie('purple-pro-banner') != "true") {
    if (document.querySelector('#proBanner')) {
      document.querySelector('#proBanner').classList.add('d-flex');
    }
    if (document.querySelector('.navbar')) {
      document.querySelector('.navbar').classList.remove('fixed-top');
    }
  } else {
    if (document.querySelector('#proBanner')) {
      document.querySelector('#proBanner').classList.add('d-none');
    }
    if (document.querySelector('.navbar')) {
      document.querySelector('.navbar').classList.add('fixed-top');
    }
  }

  if ($(".navbar").hasClass("fixed-top")) {
    if (document.querySelector('.page-body-wrapper')) {
      document.querySelector('.page-body-wrapper').classList.remove('pt-0');
    }
    if (document.querySelector('.navbar')) {
      document.querySelector('.navbar').classList.remove('pt-5');
    }
  } else {
    if (document.querySelector('.page-body-wrapper')) {
      document.querySelector('.page-body-wrapper').classList.add('pt-0');
    }
    if (document.querySelector('.navbar')) {
      document.querySelector('.navbar').classList.add('pt-5');
      document.querySelector('.navbar').classList.add('mt-3');
    }
  }

  if (document.querySelector('#bannerClose')) {
    document.querySelector('#bannerClose').addEventListener('click', function () {
      if (document.querySelector('#proBanner')) {
        document.querySelector('#proBanner').classList.add('d-none');
        document.querySelector('#proBanner').classList.remove('d-flex');
      }
      if (document.querySelector('.navbar')) {
        document.querySelector('.navbar').classList.remove('pt-5');
        document.querySelector('.navbar').classList.add('fixed-top');
        document.querySelector('.navbar').classList.remove('mt-3');
      }
      if (document.querySelector('.page-body-wrapper')) {
        document.querySelector('.page-body-wrapper').classList.add('proBanner-padding-top');
      }
      var date = new Date();
      date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
      $.cookie('purple-pro-banner', "true", {
        expires: date
      });
    });
  }
})(jQuery);