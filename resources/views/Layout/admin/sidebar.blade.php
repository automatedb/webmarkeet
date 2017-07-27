<ul class="nav flex-column">
    <li class="nav-item">
        {{ link_to_action('ContentCtrl@contents', 'Contenus', [], ['class' => 'nav-link']) }}
    </li>
    <li class="nav-item">
        {{ link_to_action('UserCtrl@profile', 'Profil', [], ['class' => 'nav-link active']) }}
    </li>
</ul>