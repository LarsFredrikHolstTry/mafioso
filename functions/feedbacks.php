<?php
//TODO: Vurder om det er behov for disse funksjonene, eller om man skal kalle feedback-funksonen med ekstra parameter direkte.
function feedback(string $msg, string $feedbackType)
{
    $icon = array(
        'error'     => 'fi-rr-cross',
        'success'   => 'fi-rr-check',
        'fail'      => 'fi-rr-exclamation',
        'blue'      => 'fi-rr-info',
        'cooldown'  => 'fi-rr-alarm-clock',
        'money'     => 'fi-rr-dollar',
        'airport'     => 'fi-rr-plane'

    );

    return '<div class="feedback ' . $feedbackType . '"><i class="fi ' . $icon[$feedbackType] . '"></i><span>' . $msg . '</span></div>';
}

function right_alert($link, $header, $text, $icon)
{
    echo '
    <a href="' . $link . '">
        <div class="margin_bottom action_container_event">
            <div class="action_icon">
                <img src="img/icons/' . $icon . '.svg">
            </div>
            <div class="action_text">
                <span style="color: white;">' . $header . '</span><br>
                <span style="color: grey; font-size: var(--font-small)">' . $text . '</span>
            </div>
            <div style="clear: both;"></div>
        </div>
    </a>';
}

function pill($msg, $type)
{
    $class = array(
        'error'     => 'chip_error',
        'success'   => 'chip_success',
        'fail'      => 'chip_fail',
        'blue'      => 'chip_blue',
        'cooldown'  => 'chip_cooldown',
        'pig'       => 'chip_pig'
    );

    return '<div class="chip ' . $class[$type] . '"><span>' . $msg . '</span></div>';
}
