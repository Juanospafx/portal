     </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="libs/js/functions.js"></script>
  <script>
    $(document).ready(function() {
      $('.sidebar-toggle-btn').on('click', function(e) {
        e.preventDefault();
        $('.sidebar').toggleClass('toggled');
        $('.page').toggleClass('toggled');
      });

      function updateTime() {
        var date = new Date();
        var options = { timeZone: 'America/New_York', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric' };
        $('#time').text(date.toLocaleDateString('en-US') + ' ' + date.toLocaleTimeString('en-US', options));
      }

      setInterval(updateTime, 1000);
      updateTime();
    });
  </script>

  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
