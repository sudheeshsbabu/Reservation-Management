jQuery('.timslot-value').on('click', function() {
  var pid = jQuery(this).data('pid');
  var url = '/form/restaurant-booking?pid=' + pid;
  window.open(url, '_blank');
});