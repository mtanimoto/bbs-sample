<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="/">
            <img src="/imgs/bbs-sample-logo.png" height="28">
        </a>
    </div>

    <div id="navbar" class="navbar-menu is-active">
        <div class="navbar-start">
            <a class="navbar-item" href="/boards">
                板一覧
            </a>
        </div>

        <?php
            $session = session();
        ?>
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <?php if ($session->has('user_id')): ?>
                    <a class="button is-primary" href="/logout">
                        Log out
                    </a>
                    <?php else: ?>
                        <a class="button is-primary" href="/signup">
                        <strong>Sign up</strong>
                    </a>
                    <a class="button is-light" href="/login">
                        Log in
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>