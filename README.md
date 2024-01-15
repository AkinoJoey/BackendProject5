# Email Verification System
Backend Project5（Servers with Databases）を管理していたリポジトリです。

Email Verification Systemの要件を満たすために、以下を作成しました。

5つのルート
- verify/email
- verify/resend
- form/verify/resend
- mypage
- form/mypage

1つのミドルウェア
- [EmailVerifiedMiddleware](https://github.com/AkinoJoey/BackendProject5/blob/main/Middleware/EmailVerifiedMiddleware.php)

2つのViews/page
- [mypage](https://github.com/AkinoJoey/BackendProject5/blob/main/Views/page/mypage.php)
- [verificationEmail](https://github.com/AkinoJoey/BackendProject5/blob/main/Views/page/verificationEmail.php)
