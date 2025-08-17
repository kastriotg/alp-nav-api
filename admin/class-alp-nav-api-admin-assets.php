<?php
/**
 * Responsible for rendering inline JavaScript for the AlpNav Plugin admin settings page.
 */
class Alp_Nav_Api_Admin_Assets {
    public static function render_test_auth_js() {
        ?>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('alpnav-test-auth');
            if (!btn) return;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var clientId = document.getElementById('alpnav_client_id') ? document.getElementById('alpnav_client_id').value : '';
                var clientSecret = document.getElementById('alpnav_client_secret') ? document.getElementById('alpnav_client_secret').value : '';
                var resultDiv = document.getElementById('alpnav-auth-result');
                resultDiv.innerHTML = 'Testing...';
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=alpnav_test_auth&client_id=' + encodeURIComponent(clientId) + '&client_secret=' + encodeURIComponent(clientSecret)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultDiv.innerHTML = '<span style="color:green;">Success</span>';
                    } else {
                        resultDiv.innerHTML = '<span style="color:red;">' + (data.data || 'Error') + '</span>';
                    }
                })
                .catch(err => {
                    resultDiv.innerHTML = '<span style="color:red;">Request failed</span>';
                });
            });
        });
        </script>
        <?php
    }
}
