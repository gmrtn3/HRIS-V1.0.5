$('#save-as-pdf').click(function() {
    var modalContent = $('.modal-body').html();
    $.ajax({
      url: '../actions/Generate_Payroll_view/generate-pdf.php',
      method: 'POST',
      data: { content: modalContent },
      success: function(response) {
        window.location.href = response;
      }
    });
  });
  alert('dsd');