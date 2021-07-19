    <style>
      body {
        background-color: #3f4144;
      }
      .ct {
        display: block;
        width: auto;
        word-wrap: break-word;
      }
      .ts {
        color: #28bd14;
      }
      .tn {
        color:#0078d7;
      }
      .tt {
        color:#000;
      }
      .ot {
        color: #747681;
      }
      .ol {
        display: block;
        width: 100%;
      }
      .er {
        color: #e63333;
      }
    </style>

    <div id="pageChat" class="grid-container full">

      <div class="reveal small" id="nameReveal" data-reveal data-animation-out="fade-out" data-close-on-click="false" data-close-on-esc="false">
        <div class="grid-x align-center-middle" style="height: 100%">
          <div class="cell small-10"><label>Enter name: (This name will be visible to others)</label>
            <div class="input-group">
              <input class="input-group-field" placeholder="Name (Required)" type="text" maxlength="18" name="chatName">
              <div class="input-group-button">
                <button v-on:click="checkName()" type="button" class="button">OK</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="padding-horizontal-1">
        <div class="grid-y grid-frame">
          <div class="cell shrink">
            <div class="grid-x align-center">
              <div class="cell small-12 medium-10 large-7 margin-top-1 padding-horizontal-1" style="background-color: #fff;">
                <div class="text-center"><h2>Chat Room</h2></div>
              </div>
            </div>
          </div>
          <div class="cell auto cell-block-container">
            <div class="grid-x align-center" style="min-height: 100%;">
              <div class="cell small-12 medium-10 large-7" style="background-color: #fff;">
                <div id="chatBox" class="bordered padding-horizontal-1 cell-block-y">
                  
                </div>
              </div>
            </div>
          </div>
          <div class="cell shrink">
            <div class="grid-x align-center">
              <div class="cell small-12 medium-10 large-7">
                <div class="input-group">
                  <input class="input-group-field" type="text" maxlength="80" name="chatInput">
                  <div class="input-group-button">
                    <button v-on:click="chatSend()" type="button" class="button">Send</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/what-input.js"></script>
    <script src="assets/js/vendor/foundation.min.js"></script>
    <script src="assets/js/vendor/vue.js"></script>