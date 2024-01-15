<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mb-4">This is my page!</h2>
            <p>Username: <?php echo $user->getUsername(); ?></p>
            <p>Email: <?php echo $user->getEmail(); ?></p>
            <form action="/form/mypage" method="post">
                <!-- フォームがcsrfトークンを使用するようになりました。 -->
                <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
                <div class="mb-3">
                    <p>If you wish to change your email address, please enter the desired email address below and press the send button.</p>
                    <label for="email" class="form-label">New Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>