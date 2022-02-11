<div class="page__landing">

  <div class="landing__header">
    <div class="landing__header-logo"></div>
    <div class="landing__header-text">
      <div class="landing__header-title">$HeaderTitle</div>
      <div class="landing__header-subtitle">$HeaderSubtitle</div>
    </div>
  </div>

  <div class="landing__subsites-container">
    <% loop $Menu(1) %>
      <% if $HomePage %>
        <a href="$Link" class="landing__subsites-index $Theme" id="$Theme" >
          <div class="subsites-index__img" style="background-image: url('$Image.GetURL');"></div>
          <div class="subsites-index__cover"></div>
          <div class="subsites-index__logo"></div>
          <div class="subsites-index__title">$Title</div>
          <div class="subsites-index__arrow"></div>
        </a>
      <% end_if %>
    <% end_loop %>
  </div>

  <div class="landing__content">
    <h2 class="landing__content-title">$BlockTitle</h2>
    <div class="landing__content-divider"></div>
    <p class="landing__content-text">$Content</p>
  </div>

  <div class="landing__footer">

    <div class="landing__footer-socials">
      <% if $SiteConfig.SocialLandingFacebook %>
        <a href="$SiteConfig.SocialLandingFacebook" class="footer__sociallinkindex facebook"></a>
      <% end_if %>
      <% if $SiteConfig.SocialLandingInstagram %>
        <a href="$SiteConfig.SocialLandingInstagram" class="footer__sociallinkindex instagram"></a>
      <% end_if %>
      <% if $SiteConfig.SocialLandingYoutube %>
        <a href="$SiteConfig.SocialLandingYoutube" class="footer__sociallinkindex youtube"></a>
      <% end_if %>
      <% if $SiteConfig.SocialLandingLinkedIn %>
        <a href="$SiteConfig.SocialLandingLinkedIn" class="footer__sociallinkindex linkedinlanding"></a>
      <% end_if %>
    </div>

    <div class="landing__footer-info">
      <% if $SiteConfig.ContactFormEmail %>
        <div class="footer__email"><a href="mailto:$SiteConfig.ContactFormEmail">$SiteConfig.ContactFormEmail</a></div>
      <% end_if %>
      <% if $SiteConfig.ContactPhoneNumber %>
        <div class="footer__phone"><a href="mailto:$SiteConfig.ContactPhoneNumber">$SiteConfig.ContactPhoneNumber</a></div>
      <% end_if %>
      <% if $SiteConfig.CharityNumber %>
        <div class="footer__charity last">Registered charity number: $SiteConfig.CharityNumber</div>
      <% end_if %>
    </div>
  </div>
</div>