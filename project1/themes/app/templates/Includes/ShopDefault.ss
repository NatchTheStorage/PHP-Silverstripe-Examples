<div id='collection-component-1634161088048'></div>
<link rel="stylesheet" href="https://use.typekit.net/qdd4qqe.css">

<script type="text/javascript">
  /*<![CDATA[*/
  (function () {
    var scriptURL = 'https://sdks.shopifycdn.com/buy-button/latest/buy-button-storefront.min.js';
    if (window.ShopifyBuy) {
      if (window.ShopifyBuy.UI) {
        ShopifyBuyInit();
      } else {
        loadScript();
      }
    } else {
      loadScript();
    }
    function loadScript() {
      var script = document.createElement('script');
      script.async = true;
      script.src = scriptURL;
      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
      script.onload = ShopifyBuyInit;
    }
    function ShopifyBuyInit() {
      var client = ShopifyBuy.buildClient({
        domain: 'philips-search-and-rescue-trust.myshopify.com',
        storefrontAccessToken: 'e626da0f13aa77c7efe0c1f1300cae49',
      });
      ShopifyBuy.UI.onReady(client).then(function (ui) {
        ui.createComponent('collection', {
          id: '287303434475',
          node: document.getElementById('collection-component-1634161088048'),
          moneyFormat: '%24%7B%7Bamount%7D%7D',
          options: {
            "product": {
              "styles": {
                "product": {
                  "text-align": "left",
                  "display": "flex",
                  "flex-direction": "column",
                  "justify-content": "space-between",
                  "@media (min-width: 601px)": {
                    "width": "calc(50% - 20px)"
                  },
                  "@media (min-width: 801px)": {
                    "width": "calc(33% - 20px)"
                  },
                  "@media (min-width: 1200px)": {
                    "width": "calc(25% - 20px)"
                  },
                  "imgWrapper": {
                    "margin-bottom": "10px",
                    "max-height": "350px"
                  },
                  "img": {
                    "margin-bottom": "0",
                    "width": "100%",
                    "@media (min-width: 1200px)": {
                      "max-height": "350px",
                    },
                  },
                },
                "title": {
                  "margin-bottom": "10px",
                  "color": "#231f20",
                  "font-family": "muli, sans serif",
                  "text-transform": "uppercase",
                  "font-size": "24px",
                  "line-height": "25px",
                  "@media (min-width: 768px)": {
                    "font-size": "28px",
                    "line-height": "26px",
                  },
                  "@media (min-width: 1440px)": {
                    "font-size": "30px",
                    "line-height": "30px",
                  }
                },
                "prices": {
                  "margin-bottom": "20px",
                  "media (min-width: 768px)": {
                    "margin-bottom": "15px",
                  },
                  "media (min-width: 1440px)": {
                    "margin-bottom": "20px",
                  },
                },
                "price": {
                  "margin-bottom": "0px",
                  "color": "$PrimaryColour",
                  "font-family": "Proxima Nova, sans-serif",
                  "font-size": "20px",
                  "line-height": "22px",
                  "media (min-width: 768px)": {
                    "font-size": "24px",
                    "line-height": "24px",
                  },
                  "media (min-width:1440px)": {
                    "font-size": "24px",
                    "line-height": "24px"
                  }
                },
                "unitPrice": {
                  "font-size": "15.299999999999999px"
                },
                "buttonWrapper": {
                  "margin-top": "0",
                  "text-align": "right",
                  "order": "5",
                  "media (min-width: 768px)": {
                    "margin-bottom": "200px"
                  }
                },
                "button": {
                  "font-family": "Proxima Nova, sans-serif",
                  "cursor": "pointer",
                  "display": "inline-block",
                  "position": "relative",
                  "color": "white",
                  "-webkit-transform": "perspective(1px) translateZ(0)",
                  "transform": "perspective(1px) translateZ(0)",
                  "box-shadow": "0 0 1px rgba(0, 0, 0, 0)",
                  "-webkit-transition-property": "color",
                  "transition-property": "color",
                  "-webkit-transition-duration": "1",
                  "transition-duration": "1",
                  "text-transform": "uppercase",
                  "border": "1px solid transparent",
                  "padding": "16px 21px",
                  "width": "100%",
                  "border-radius": "5px",
                  "text-decoration": "none",
                  "background-color": "$PrimaryColour",
                  ":hover": {
                    "background-color": "$SecondaryColour"
                  },
                  ":focus": {
                    "background-color": "$SecondaryColour"
                  },
                  "media(min-width:768px)": {
                    "padding": "18px 21px",
                  },
                  "media(min-width:1440px)": {
                    "padding": "20px 21px",

                  }
                },
              },
              "buttonDestination": "modal",
              "contents": {
                "options": false
              },
              "text": {
                "button": "View product"
              }
            },
            "productSet": {
              "styles": {
                "products": {
                  "display" : "flex",
                  "flex-wrap" : "wrap",
                  "margin" :"0",
                  "@media (min-width: 767px)": {
                    "margin-left": "-10px",
                    "margin-right": "-10px",
                  },
                  "@media (min-width: 1439px)": {
                    "margin-left": "-15px",
                    "margin-right": "-15px",
                  },
                }
              }
            },
            "modalProduct": {
              "contents": {
                "img": false,
                "imgWithCarousel": true,
                "button": false,
                "buttonWithQuantity": true
              },
              "styles": {
                "product": {
                  "@media (min-width: 768px)": {
                    "max-width": "100%",
                    "margin-left": "0px",
                    "margin-bottom": "0px"
                  }
                },
                "button": {
                  ":hover": {
                    "background-color": "$SecondaryColour"
                  },
                  "background-color": "$PrimaryColour",
                  ":focus": {
                    "background-color": "$SecondaryColour"
                  },
                  "border-radius": "4px"
                },
                "title": {
                  "font-family": "Bebas Neue Pro, sans-serif",
                  "font-weight": "bold",
                  "font-size": "26px",
                  "color": "#4c4c4c"
                }
              },
              "text": {
                "button": "Add to cart"
              }
            },
            "option": {},
            "cart": {
              "styles": {
                "button": {
                  ":hover": {
                    "background-color": "$SecondaryColour"
                  },
                  "background-color": "$PrimaryColour",
                  ":focus": {
                    "background-color": "$SecondaryColour"
                  },
                  "border-radius": "4px"
                }
              },
              "text": {
                "total": "Subtotal",
                "button": "Checkout"
              },
              "contents": {
                "note": true
              }
            },
            "toggle": {
              "styles": {
                "toggle": {
                  "background-color": "$PrimaryColour",
                  ":hover": {
                    "background-color": "$SecondaryColour"
                  },
                  ":focus": {
                    "background-color": "$SecondaryColour"
                  }
                }
              }
            }
          },
        });
      });
    }
  })();
  /*]]>*/
</script>