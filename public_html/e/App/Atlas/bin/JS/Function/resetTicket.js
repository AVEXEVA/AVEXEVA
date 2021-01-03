function resetTicket(link){
  if (confirm('Are you sure you want to reset the ticket? All times will be reset to null.')) {
    $.ajax({
      url : 'bin/PHP/POST/ticket_time_reset.php',
      data : {
        ID : null
      },
      method:'POST',
      success:function(){document.location.href='Ticket.php?ID=' + null;}
    });
  }
}
