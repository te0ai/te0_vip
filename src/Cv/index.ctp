<div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
        <div class="d-flex flex-column align-content-end">
            <div class="app-auth-body mx-auto">
                <div class="app-auth-branding mb-4"><img class="me-2" src="<?php h(_HOME_) ?>img/common/logo.webp" alt="logo" style="height:50px;"></div>
                <h2 class="auth-heading text-center mb-5">ログイン</h2>
                <div class="auth-form-container text-start">
                    <form class="auth-form login-form" method="post" action="">
                        <div class="email mb-3">
                            <label class="sr-only" for="email">メールアドレス</label>
                            <input id="email" name="email" type="email" class="form-control signin-email" placeholder="Email address" required="required" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
                        </div>
                        <div class="password mb-3">
                            <label class="sr-only" for="password">パスワード</label>
                            <input id="password" name="password" type="password" class="form-control signin-password" placeholder="Password" required="required" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
                            <div class="extra mt-3 row justify-content-between">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="RememberPassword" name="RememberPassword" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="RememberPassword">パスワードを記憶する</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password text-end">
                                        <a href="https://te0.ai/login/">IDパスワードはテレAIと共通です</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">ログイン</button>
                        </div>
                    </form>

                    <div class="auth-option text-center pt-5">テレAIについては<a class="text-link" href="https://te0.jp/">こちら</a></div>
                </div><!--//auth-form-container-->

            </div><!--//auth-body-->

            <footer class="app-auth-footer">
                <div class="container text-center py-3">
                    <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                    <small class="copyright">Designed with <span class="sr-only">love</span><svg class="svg-inline--fa fa-heart" style="color: #fb866a;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                            <path fill="currentColor" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"></path>
                        </svg><!-- <i class="fas fa-heart" style="color: #fb866a;"></i> Font Awesome fontawesome.com --> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>

                </div>
            </footer><!--//app-auth-footer-->
        </div><!--//flex-column-->
    </div><!--//auth-main-col-->
    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
        <div class="auth-background-holder" style="background:url('<?php h(_HOME_) ?>img/common/hero.webp') no-repeat center center;background-size:cover;height:100vh;min-height:100%">
        </div>
        <div class="auth-background-mask"></div>
        <div class="auth-background-overlay p-3 p-lg-5">
            <div class="d-flex flex-column align-content-end h-100">
                <div class="h-100"></div>
                <div class="overlay-content p-3 p-lg-4 rounded">
                    <h5 class="mb-3 overlay-title">特別対応サイト</h5>
                    <div>特別なお客様の対応サイト</div>
                </div>
            </div>
        </div><!--//auth-background-overlay-->
    </div><!--//auth-background-col-->

</div><!--//row-->