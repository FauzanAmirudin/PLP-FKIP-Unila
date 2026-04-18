<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Main Core Error 
|--------------------------------------------------------------------------
|
| Initial Core Error Helper aplications
|
*/
?>
<style>
    code.gf_debug .xdebug-var-dump {
        width: 100% !important;
        display: block;
        max-width: calc(100% - 20px);
    }
</style>
<code class="gf_debug">
    <?php
    ob_start();
    var_dump($_SESSION);

    echo '<br/>';
    if (GF_ENVIRONMENT == 'debug') {
        print_r(GF_ENVIRONMENT);
        echo '<br/>';
        print_r(GF_MODE);
        echo '<br/>';
        print_r(GF_APP_PATH);
        echo '<br/>';
        print_r(GF_GONFIG_PATH);
        echo '<br/>';
        print_r(GF_CONTROL_PATH);
        echo '<br/>';
        print_r(GF_MODEL_PATH);
        echo '<br/>';
        print_r(GF_HLP_PATH);
        echo '<br/>';
        print_r(GF_REQUEST);
    };
    $result = ob_get_clean();
    echo $result;
    ?>
</code>
<script>
    const gf_error = function() {
        let init_window = function(e_window) {
            var posX = 0,
                posY = 0,
                changeX = 0,
                changeY = 0;
            let movers = e_window.getElementsByClassName("gf_error_window_mover");
            if (movers.length > 0) {
                // if present, the mover is where you move the DIV from:
                for (const mover of movers) {
                    mover.onmousedown = dragMouseDown;
                }
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }
            let close_btn = e_window.getElementsByClassName("gf_error_window_hide_btn");
            if (close_btn.length > 0) {
                // if present, the close button is
                for (const btn of close_btn) {
                    btn.onmousedown = closeWindow;
                }
            }

            function closeWindow(e) {
                e.target.closest(".gf_error_window").style.display = 'none';
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                posX = e.clientX;
                posY = e.clientY;
                e_window.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                e_window.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                changeX = posX - e.clientX;
                changeY = posY - e.clientY;
                console.log(posX, posY, changeX, changeY)
                posX = e.clientX;
                posY = e.clientY;
                // set the element's new position:
                console.log(e_window.style.top, e_window.style.left);
                e_window.style.top = (e_window.offsetTop - changeY) + "px";
                e_window.style.left = (e_window.offsetLeft - changeX) + "px";
                console.log(e_window.style.top, e_window.style.left);
                console.log("------------------");
            }

            function closeDragElement() {
                /* stop moving when mouse button is released:*/
                e_window.onmouseup = null;
                e_window.onmousemove = null;
            }
        }
        let init_messege = function(messege) {
            var posX = 0,
                posY = 0,
                changeX = 0,
                changeY = 0;
            let windows = messege.getElementsByClassName("gf_error_window");
            if (windows.length > 0) {
                // if present, the windows is where error messege actualy placed.
                for (const e_win of windows) {
                    init_window(e_win);
                }
            }
            let open_btns = messege.getElementsByClassName("gf_error_window_show_btn");
            if (open_btns.length > 0) {
                // if present, the the show button is
                for (const btn of open_btns) {
                    btn.onmousedown = openWindow;
                }
            }

            function openWindow(e) {
                e = e || window.event;
                e.preventDefault();
                let e_window = e.target.nextElementSibling;
                e_window.style.display = 'block';
                e_window.onmouseup = null;
                e_window.onmousemove = null;
            }
        }
        let e_messege = document.getElementsByClassName('gf_error_messege');
        if (e_messege.length > 0) {
            for (const element of e_messege) {
                init_messege(element);
            }
        }
    }
    var gf_open_error_window = function(e) {
        e = e || window.event;
        let e_window = e.nextElementSibling;
        console.log(e_window);
        e_window.style.display = 'block';
        e_window.onmouseup = null;
        e_window.onmousemove = null;
    }
    var gf_close_error_window = function(e) {
        e = e || window.event;
        e.parentElement.parentElement.parentElement.style.display = 'none';
    }
    var gf_init_error_windows = function(e_window) {
        var posX = 0,
            posY = 0,
            changeX = 0,
            changeY = 0;
        let movers = e_window.getElementsByClassName("gf_error_window_mover");
        if (movers.length > 0) {
            // if present, the mover is where you move the DIV from:
            for (const mover of movers) {
                mover.onmousedown = dragMouseDown;
            }
        } else {
            // otherwise, move the DIV from anywhere inside the DIV:
            elmnt.onmousedown = dragMouseDown;
        }
        let close_btn = e_window.getElementsByClassName("gf_error_window_hide_btn");
        if (close_btn.length > 0) {
            // if present, the close button is
            for (const btn of close_btn) {
                btn.onmousedown = closeWindow;
            }
        }

        function closeWindow(e) {
            e.target.closest(".gf_error_window").style.display = 'none';
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            posX = e.clientX;
            posY = e.clientY;
            e_window.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            e_window.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            changeX = posX - e.clientX;
            changeY = posY - e.clientY;
            console.log(posX, posY, changeX, changeY)
            posX = e.clientX;
            posY = e.clientY;
            // set the element's new position:
            console.log(e_window.style.top, e_window.style.left);
            e_window.style.top = (e_window.offsetTop - changeY) + "px";
            e_window.style.left = (e_window.offsetLeft - changeX) + "px";
            console.log(e_window.style.top, e_window.style.left);
            console.log("------------------");
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            e_window.onmouseup = null;
            e_window.onmousemove = null;
        }
    }
    // window.ready = gf_error()
</script>