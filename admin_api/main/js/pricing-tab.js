
// =================== tab-section-js ======================

var tabs = $('.tab-li');
var tabContent = $('.tab-content');

tabs.on('click', function() {
  tabs.removeClass('active');

  $(this).addClass('active');

  tabContent.removeClass('active');

  var activeTab = $(this).attr("rel");

  $("#"+activeTab).addClass('active');
});

var verticalHeader = $('.vertical-header');

verticalHeader.on('click', function() {
  verticalHeader.removeClass('active');

  $(this).addClass('active');

  tabContent.removeClass('active');

  var activeTab = $(this).attr("rel");

  $("#"+activeTab).addClass('active');
});

// =================== tab-section-js ======================