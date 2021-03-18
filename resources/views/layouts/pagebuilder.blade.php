<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ config('app.name') }} | Page Builder</title>
        <meta content="Best Free Open Source Responsive Websites Builder" name="description">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/grapes.min.css?v0.16.34') }}">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/grapesjs-preset-webpage.min.css') }}">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/tooltip.css') }}">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/grapesjs-plugin-filestack.css') }}">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/demos.css?v3') }}">
        <link href="{{ url('library/plugins/pagebuilder/css/grapick.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ url('library/plugins/pagebuilder/css/custom.css') }}">
        
        <link rel="stylesheet" href="{{ url('library/plugins/sweetalert/dist/sweetalert.css') }}">
        <script type="text/javascript" src="{{ url('library/plugins/sweetalert/sweetalert_old.min.js')}}"></script>

        <!-- <script src="//static.filestackapi.com/v3/filestack.js"></script> -->
        <!-- <script src="https://grapesjs.com/js/aviary.js"></script> old //feather.aviary.com/imaging/v3/editor.js -->
        
        <script src="{{ url('library/plugins/pagebuilder/js/jquery.min.js') }}"></script>
        
        <script src="{{ url('library/plugins/pagebuilder/js/toastr.min.js') }}"></script>

        <script src="{{ url('library/plugins/pagebuilder/js/grapes.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-preset-webpage.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-lory-slider.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-tabs.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-custom-code.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-touch.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-parser-postcss.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-tooltip.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-tui-image-editor.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-typed.min.js') }}"></script>
        <script src="{{ url('library/plugins/pagebuilder/js/grapesjs-style-bg.min.js') }}"></script>

    </head>
    <body data-url="{{ url('/') }}">
        @yield('content')
        <div class="ad-cont">
            <!-- <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?zoneid=1673&serve=C6AILKT&placement=grapesjscom" id="_carbonads_js"></script> -->
            <div id="native-carbon"></div>
            <script async type="text/javascript" src="{{ url('library/plugins/pagebuilder/js/carbon.js') }}"></script>
        </div>

        <div id="gjs" style="height:0px; overflow:hidden"></div>

        <script type="text/javascript">
            var CSRF_TOKEN = '{{ csrf_token() }}';
            var lp = '{{url("/library/plugins/pagebuilder/images/")}}';
            var plp = '//placehold.it/350x250/';
            var images = [
                lp+'team1.jpg', lp+'team2.jpg', lp+'team3.jpg', plp+'78c5d6/fff/image1.jpg', plp+'459ba8/fff/image2.jpg', plp+'79c267/fff/image3.jpg',
                plp+'c5d647/fff/image4.jpg', plp+'f28c33/fff/image5.jpg', plp+'e868a2/fff/image6.jpg', plp+'cc4360/fff/image7.jpg',
                lp+'work-desk.jpg', lp+'phone-app.png', lp+'bg-gr-v.png'
            ];

          var editor  = grapesjs.init({
              avoidInlineStyle: 1,
              height: '100%',
              container : '#gjs',
              fromElement: 1,
              showOffsets: 1,
              assetManager: {
                  embedAsBase64: 1,
                  assets: images
              },
              storageManager: {
                  type: 'remote',
                  stepsBeforeSave: 1,
                  urlStore: '{{ $reload_page_api }}',
                  urlLoad: '{{ $reload_page_api }}',
                  // For custom parameters/headers on requests
                  // params: { _some_token: '....' },
                  // headers: { Authorization: 'Basic ...' },
              },
              selectorManager: { componentFirst: true },
              styleManager: { clearProperties: 1 },
              plugins: [
                  'grapesjs-lory-slider',
                  'grapesjs-tabs',
                  'grapesjs-custom-code',
                  'grapesjs-touch',
                  'grapesjs-parser-postcss',
                  'grapesjs-tooltip',
                  'grapesjs-tui-image-editor',
                  'grapesjs-typed',
                  'grapesjs-style-bg',
                  'gjs-preset-webpage',
              ],
              pluginsOpts: {
                  'grapesjs-lory-slider': {
                      sliderBlock: {
                          category: 'Extra'
                      }
                  },
                  'grapesjs-tabs': {
                      tabsBlock: {
                          category: 'Extra'
                      }
                  },
                  'grapesjs-typed': {
                      block: {
                          category: 'Extra',
                          content: {
                              type: 'typed',
                              'type-speed': 40,
                              strings: [
                                  'Text row one',
                                  'Text row two',
                                  'Text row three',
                              ],
                          }
                      }
                  },
                  'gjs-preset-webpage': {
                      modalImportTitle: 'Import Template',
                      modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                      modalImportContent: function(editor) {
                          return editor.getHtml() + '<style>'+editor.getCss()+'</style>'
                      },
                      filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
                      aviaryOpts: false,
                      blocksBasicOpts: { flexGrid: 1 },
                      customStyleManager: [
                          {
                              name: 'General',
                              buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
                              properties:[
                                  {
                                      name: 'Alignment',
                                      property: 'float',
                                      type: 'radio',
                                      defaults: 'none',
                                      list: [
                                          { value: 'none', className: 'fa fa-times'},
                                          { value: 'left', className: 'fa fa-align-left'},
                                          { value: 'right', className: 'fa fa-align-right'}
                                      ],
                                  },
                                  { property: 'position', type: 'select'}
                              ],
                          },
                          {
                              name: 'Dimension',
                              open: false,
                              buildProps: ['width', 'flex-width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
                              properties: [
                                {
                                    id: 'flex-width',
                                    type: 'integer',
                                    name: 'Width',
                                    units: ['px', '%'],
                                    property: 'flex-basis',
                                    toRequire: 1,
                                },
                                {
                                    property: 'margin',
                                    properties:[
                                        { name: 'Top', property: 'margin-top'},
                                        { name: 'Right', property: 'margin-right'},
                                        { name: 'Bottom', property: 'margin-bottom'},
                                        { name: 'Left', property: 'margin-left'}
                                    ],
                                },
                                {
                                    property  : 'padding',
                                    properties:[
                                        { name: 'Top', property: 'padding-top'},
                                        { name: 'Right', property: 'padding-right'},
                                        { name: 'Bottom', property: 'padding-bottom'},
                                        { name: 'Left', property: 'padding-left'}
                                    ],
                                }
                              ],
                          },
                          {
                              name: 'Typography',
                              open: false,
                              buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
                              properties:
                              [
                                  { name: 'Font', property: 'font-family'},
                                  { name: 'Weight', property: 'font-weight'},
                                  { name:  'Font color', property: 'color'},
                                  {
                                      property: 'text-align',
                                      type: 'radio',
                                      defaults: 'left',
                                      list: [
                                        { value : 'left',  name : 'Left',    className: 'fa fa-align-left'},
                                        { value : 'center',  name : 'Center',  className: 'fa fa-align-center' },
                                        { value : 'right',   name : 'Right',   className: 'fa fa-align-right'},
                                        { value : 'justify', name : 'Justify',   className: 'fa fa-align-justify'}
                                      ],
                                  },
                                  {
                                      property: 'text-decoration',
                                      type: 'radio',
                                      defaults: 'none',
                                      list: [
                                          { value: 'none', name: 'None', className: 'fa fa-times'},
                                          { value: 'underline', name: 'underline', className: 'fa fa-underline' },
                                          { value: 'line-through', name: 'Line-through', className: 'fa fa-strikethrough'}
                                      ],
                                  },
                                  {
                                      property: 'text-shadow',
                                      properties: [
                                          { name: 'X position', property: 'text-shadow-h'},
                                          { name: 'Y position', property: 'text-shadow-v'},
                                          { name: 'Blur', property: 'text-shadow-blur'},
                                          { name: 'Color', property: 'text-shadow-color'}
                                      ],
                                  }
                              ],
                          },
                          {
                              name: 'Decorations',
                              open: false,
                              buildProps: ['opacity', 'border-radius', 'border', 'box-shadow', 'background-bg'],
                              properties: [
                                  {
                                      type: 'slider',
                                      property: 'opacity',
                                      defaults: 1,
                                      step: 0.01,
                                      max: 1,
                                      min:0,
                                  },
                                  {
                                      property: 'border-radius',
                                      properties  : [
                                          { name: 'Top', property: 'border-top-left-radius'},
                                          { name: 'Right', property: 'border-top-right-radius'},
                                          { name: 'Bottom', property: 'border-bottom-left-radius'},
                                          { name: 'Left', property: 'border-bottom-right-radius'}
                                      ],
                                  },
                                  {
                                      property: 'box-shadow',
                                      properties: [
                                          { name: 'X position', property: 'box-shadow-h'},
                                          { name: 'Y position', property: 'box-shadow-v'},
                                          { name: 'Blur', property: 'box-shadow-blur'},
                                          { name: 'Spread', property: 'box-shadow-spread'},
                                          { name: 'Color', property: 'box-shadow-color'},
                                          { name: 'Shadow type', property: 'box-shadow-type'}
                                      ],
                                  },
                                  {
                                    id: 'background-bg',
                                    property: 'background',
                                    type: 'bg',
                                  },
                              ],
                          },
                          {
                              name: 'Extra',
                              open: false,
                              buildProps: ['transition', 'perspective', 'transform'],
                              properties: [
                                  {
                                      property: 'transition',
                                      properties:[
                                          { name: 'Property', property: 'transition-property'},
                                          { name: 'Duration', property: 'transition-duration'},
                                          { name: 'Easing', property: 'transition-timing-function'}
                                      ],
                                  },
                                  {
                                      property: 'transform',
                                      properties:[
                                          { name: 'Rotate X', property: 'transform-rotate-x'},
                                          { name: 'Rotate Y', property: 'transform-rotate-y'},
                                          { name: 'Rotate Z', property: 'transform-rotate-z'},
                                          { name: 'Scale X', property: 'transform-scale-x'},
                                          { name: 'Scale Y', property: 'transform-scale-y'},
                                          { name: 'Scale Z', property: 'transform-scale-z'}
                                      ],
                                  }
                              ]
                          },
                          {
                              name: 'Flex',
                              open: false,
                              properties: [
                                  {
                                      name: 'Flex Container',
                                      property: 'display',
                                      type: 'select',
                                      defaults: 'block',
                                      list: [
                                          { value: 'block', name: 'Disable'},
                                          { value: 'flex', name: 'Enable'}
                                      ],
                                  },
                              {
                              name: 'Flex Parent',
                              property: 'label-parent-flex',
                              type: 'integer',
                          },
                          {
                              name : 'Direction',
                              property : 'flex-direction',
                              type : 'radio',
                              defaults : 'row',
                              list : [
                                  {
                                      value   : 'row',
                                      name    : 'Row',
                                      className : 'icons-flex icon-dir-row',
                                      title   : 'Row',
                                  },
                                  {
                                      value   : 'row-reverse',
                                      name    : 'Row reverse',
                                      className : 'icons-flex icon-dir-row-rev',
                                      title   : 'Row reverse',
                                  },
                                  {
                                      value   : 'column',
                                      name    : 'Column',
                                      title   : 'Column',
                                      className : 'icons-flex icon-dir-col',
                                  },
                                  {
                                      value   : 'column-reverse',
                                      name    : 'Column reverse',
                                      title   : 'Column reverse',
                                      className : 'icons-flex icon-dir-col-rev',
                                  }
                              ],
                          },
                          {
                              name : 'Justify',
                              property : 'justify-content',
                              type : 'radio',
                              defaults : 'flex-start',
                              list : [
                                  {
                                      value   : 'flex-start',
                                      className : 'icons-flex icon-just-start',
                                      title   : 'Start',
                                  },
                                  {
                                      value   : 'flex-end',
                                      title    : 'End',
                                      className : 'icons-flex icon-just-end',
                                  },
                                  {
                                      value   : 'space-between',
                                      title    : 'Space between',
                                      className : 'icons-flex icon-just-sp-bet',
                                  },
                                  {
                                      value   : 'space-around',
                                      title    : 'Space around',
                                      className : 'icons-flex icon-just-sp-ar',
                                  },
                                  {
                                      value   : 'center',
                                      title    : 'Center',
                                      className : 'icons-flex icon-just-sp-cent',
                                  }
                              ],
                          },
                          {
                              name : 'Align',
                              property : 'align-items',
                              type : 'radio',
                              defaults : 'center',
                              list : [
                                  {
                                      value   : 'flex-start',
                                      title    : 'Start',
                                      className : 'icons-flex icon-al-start',
                                  },
                                  {
                                      value   : 'flex-end',
                                      title    : 'End',
                                      className : 'icons-flex icon-al-end',
                                  },
                                  {
                                      value   : 'stretch',
                                      title    : 'Stretch',
                                      className : 'icons-flex icon-al-str',
                                  },
                                  {
                                      value   : 'center',
                                      title    : 'Center',
                                      className : 'icons-flex icon-al-center',
                                  }
                              ],
                          },
                          {
                              name: 'Flex Children',
                              property: 'label-parent-flex',
                              type: 'integer',
                          },
                          {
                              name:     'Order',
                              property:   'order',
                              type:     'integer',
                              defaults :  0,
                              min: 0
                          },
                          {
                              name    : 'Flex',
                              property  : 'flex',
                              type    : 'composite',
                              properties  : [
                                  {
                                      name:     'Grow',
                                      property:   'flex-grow',
                                      type:     'integer',
                                      defaults :  0,
                                      min: 0
                                  },
                                  {
                                      name:     'Shrink',
                                      property:   'flex-shrink',
                                      type:     'integer',
                                      defaults :  0,
                                      min: 0
                                  },
                                  {
                                      name:     'Basis',
                                      property:   'flex-basis',
                                      type:     'integer',
                                      units:    ['px','%',''],
                                      unit: '',
                                      defaults :  'auto',
                                  }
                              ],
                          },
                          {
                              name : 'Align',
                              property : 'align-self',
                              type : 'radio',
                              defaults : 'auto',
                              list : [
                                  {
                                      value   : 'auto',
                                      name    : 'Auto',
                                  },
                                  {
                                      value   : 'flex-start',
                                      title    : 'Start',
                                      className : 'icons-flex icon-al-start',
                                  },
                                  {
                                      value   : 'flex-end',
                                      title    : 'End',
                                      className : 'icons-flex icon-al-end',
                                  },
                                  {
                                      value   : 'stretch',
                                      title    : 'Stretch',
                                      className : 'icons-flex icon-al-str',
                                  },
                                  {
                                      value   : 'center',
                                      title    : 'Center',
                                      className : 'icons-flex icon-al-center',
                                  }
                              ],
                          }
                      ]
                  }],
                  },
              },
          });

          editor.Panels.addButton
          ('options',
              [{
                  id: 'save-db',
                  className: 'fa fa-floppy-o',
                  command: 'save-db',
                  attributes: {title: 'Save DB'}
              }]
          );
          
        // Add the command
        editor.Commands.add
        ('save-db',
        {
            run: function(editor, sender)
            {
              sender && sender.set('active', 0); // turn off the button
              editor.store();

              var htmldata = editor.getHtml();
              var cssdata = editor.getCss();
              console.log(htmldata);
              console.log(cssdata);
              $.ajax({
                  type: "PUT",
                  url: "{{ url('admin/pagebuilder') }}/"+{{ $page_id }}+"/build",
                  dataType: "json",
                  headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
                  data: {
                      "html" : htmldata, 
                      "css" : cssdata
                  },
                  success: function (response) {
                      console.log(response);
                      swal({
                          title : "Congratulations!",
                          text : "Page successfully built",
                          icon : "success", 
                          buttons: "Ok",
                      })
                      $('.swal-button--confirm').click(function () {
                          window.history.back();
                      });
                  }
              });
            }
        });
      
        editor.I18n.addMessages({
            en: {
                styleManager: {
                    properties: {
                      'background-repeat': 'Repeat',
                      'background-position': 'Position',
                      'background-attachment': 'Attachment',
                      'background-size': 'Size',
                    }
                },
            }
        });

        var pn = editor.Panels;
        var modal = editor.Modal;
        var cmdm = editor.Commands;
        cmdm.add('canvas-clear', function() {
            if(confirm('Areeee you sure to clean the canvas?')) {
                var comps = editor.DomComponents.clear();
                setTimeout(function(){ localStorage.clear()}, 0)
            }
        });
        cmdm.add('set-device-desktop', {
            run: function(ed) { ed.setDevice('Desktop') },
            stop: function() {},
        });
        cmdm.add('set-device-tablet', {
            run: function(ed) { ed.setDevice('Tablet') },
            stop: function() {},
        });
        cmdm.add('set-device-mobile', {
            run: function(ed) { ed.setDevice('Mobile portrait') },
            stop: function() {},
        });



          // Add info command
          var mdlClass = 'gjs-mdl-dialog-sm';
          var infoContainer = document.getElementById('info-panel');
          cmdm.add('open-info', function() {
              var mdlDialog = document.querySelector('.gjs-mdl-dialog');
              mdlDialog.className += ' ' + mdlClass;
              infoContainer.style.display = 'block';
              modal.setTitle('About this demo');
              modal.setContent(infoContainer);
              modal.open();
              modal.getModel().once('change:open', function() {
                  mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
              })
          });
          pn.addButton('options', {
              id: 'open-info',
              className: 'fa fa-question-circle',
              command: function() { editor.runCommand('open-info') },
              attributes: {
                  'title': 'About',
                  'data-tooltip-pos': 'bottom',
              },
          });


          // Simple warn notifier
          var origWarn = console.warn;
          toastr.options = {
              closeButton: true,
              preventDuplicates: true,
              showDuration: 250,
              hideDuration: 150
          };
          console.warn = function (msg) {
              if (msg.indexOf('[undefined]') == -1) {
                  toastr.warning(msg);
              }
              origWarn(msg);
          };


          // Add and beautify tooltips
          [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
          ['export-template', 'Export'], ['undo', 'Undo'], ['redo', 'Redo'],
          ['gjs-open-import-webpage', 'Import'], ['canvas-clear', 'Clear canvas']]
          .forEach(function(item) {
            pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
          });
          [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
          .forEach(function(item) {
            pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
          });
          var titles = document.querySelectorAll('*[title]');

          for (var i = 0; i < titles.length; i++) {
            var el = titles[i];
            var title = el.getAttribute('title');
            title = title ? title.trim(): '';
            if(!title)
              break;
            el.setAttribute('data-tooltip', title);
            el.setAttribute('title', '');
          }

          // Show borders by default
          pn.getButton('options', 'sw-visibility').set('active', 1);


          // Store and load events
          editor.on('storage:load', function(e) { console.log('Loaded ', e) });
          editor.on('storage:store', function(e) { console.log('Stored ', e) });


          // Do stuff on load
          editor.on('load', function() {
            var $ = grapesjs.$;

            // Show logo with the version
            var logoCont = document.querySelector('.gjs-logo-cont');
            document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
            var logoPanel = document.querySelector('.gjs-pn-commands');
            logoPanel.appendChild(logoCont);


            // Load and show settings and style manager
            var openTmBtn = pn.getButton('views', 'open-tm');
            openTmBtn && openTmBtn.set('active', 1);
            var openSm = pn.getButton('views', 'open-sm');
            openSm && openSm.set('active', 1);

            // Add Settings Sector
            var traitsSector = $('<div class="gjs-sm-sector no-select">'+
              '<div class="gjs-sm-title"><span class="icon-settings fa fa-cog"></span> Settings</div>' +
              '<div class="gjs-sm-properties" style="display: none;"></div></div>');
            var traitsProps = traitsSector.find('.gjs-sm-properties');
            traitsProps.append($('.gjs-trt-traits'));
            $('.gjs-sm-sectors').before(traitsSector);
            traitsSector.find('.gjs-sm-title').on('click', function(){
              var traitStyle = traitsProps.get(0).style;
              var hidden = traitStyle.display == 'none';
              if (hidden) {
                traitStyle.display = 'block';
              } else {
                traitStyle.display = 'none';
              }
            });

            // Open block manager
            var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
            openBlocksBtn && openBlocksBtn.set('active', 1);

            // Move Ad
            $('#gjs').append($('.ad-cont'));
          });

          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-74284223-1', 'auto');
          ga('send', 'pageview');
        </script>
    </body>
</html>
