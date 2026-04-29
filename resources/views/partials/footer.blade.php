{{-- Footer --}}
<footer class="footer">
    <div class="footer-glow"></div>
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="footer-logo">
                    <span>Wallpaper<span class="text-wh-accent">Hunt</span></span>
                </a>
                <p class="footer-description">
                    Cộng đồng chia sẻ hình nền chất lượng cao. Khám phá hàng ngàn wallpaper đẹp từ các nghệ sĩ tài năng trên khắp thế giới.
                </p>
                <div class="footer-social">
                    @if($fb = \App\Models\Setting::get('social_facebook'))
                        <a href="{{ $fb }}" target="_blank" class="footer-social-link" aria-label="Facebook"><i class="ph ph-facebook-logo"></i></a>
                    @endif
                    @if($insta = \App\Models\Setting::get('social_instagram'))
                        <a href="{{ $insta }}" target="_blank" class="footer-social-link" aria-label="Instagram"><i class="ph ph-instagram-logo"></i></a>
                    @endif
                    @if($twitter = \App\Models\Setting::get('social_twitter'))
                        <a href="{{ $twitter }}" target="_blank" class="footer-social-link" aria-label="Twitter"><i class="ph ph-twitter-logo"></i></a>
                    @endif
                    @if($yt = \App\Models\Setting::get('social_youtube'))
                        <a href="{{ $yt }}" target="_blank" class="footer-social-link" aria-label="Youtube"><i class="ph ph-youtube-logo"></i></a>
                    @endif
                    @if($tiktok = \App\Models\Setting::get('social_tiktok'))
                        <a href="{{ $tiktok }}" target="_blank" class="footer-social-link" aria-label="Tiktok"><i class="ph ph-tiktok-logo"></i></a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="footer-links-group">
                <h4 class="footer-heading">Khám phá</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('explore') }}">Trending</a></li>
                    <li><a href="{{ route('explore', ['sort' => 'newest']) }}">Mới nhất</a></li>
                    <li><a href="{{ route('explore', ['sort' => 'popular']) }}">Phổ biến</a></li>
                    <li><a href="{{ route('categories.index') }}">Danh mục</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div class="footer-links-group">
                <h4 class="footer-heading">Cộng đồng</h4>
                <ul class="footer-links">
                    <li><a href="#">Trở thành nghệ sĩ</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="footer-links-group">
                <h4 class="footer-heading">Pháp lý</h4>
                <ul class="footer-links">
                    <li><a href="#">Điều khoản</a></li>
                    <li><a href="#">Chính sách riêng tư</a></li>
                    <li><a href="#">Bản quyền</a></li>
                    <li><a href="#">DMCA</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} WallpaperHunt. Được tạo với <i class="ph-fill ph-heart text-red-400"></i> tại Việt Nam.</p>
        </div>
    </div>
</footer>
