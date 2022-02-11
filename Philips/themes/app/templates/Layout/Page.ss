<div class="landing__header">
  <div class="landing__header-logo"></div>
  <div class="landing__header-text">
    <div class="landing__header-title">Philips Search and Rescue</div>
    <div class="landing__header-subtitle">Login Page</div>
  </div>
</div>
<div class="content u-container u-spacing--y">
    $Content
    <% if $Form %>
        $Form
    <% end_if %>
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
      <div class="footer__email">$SiteConfig.ContactFormEmail</div>
    <% end_if %>
    <% if $SiteConfig.ContactPhoneNumber %>
      <div class="footer__phone">$SiteConfig.ContactPhoneNumber</div>
    <% end_if %>
    <% if $SiteConfig.CharityNumber %>
      <div class="footer__charity last">Registered charity number: $SiteConfig.CharityNumber</div>
    <% end_if %>
  </div>
</div>