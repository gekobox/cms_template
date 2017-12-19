<ul class="side-nav-links">
<li><a href="/#/pos"><i class="material-icons">payment</i>{{ __('navigation.pos') }}</a></li>
<li><a class="dropdown-button" data-activates="settings-dropdown" data-beloworigin="true"><i class="material-icons">settings</i>{{ __('navigation.settings') }}<i class="material-icons right">arrow_drop_down</i></a></li>
<ul id='settings-dropdown' class='dropdown-content'>
    <li><a href="/#/my-account">{{ __('navigation.my_account') }}</a></li>
</ul>
<li><a href="/#/logout"><i class="material-icons">lock</i>{{ __('navigation.log_out') }}</a></li>
</ul>