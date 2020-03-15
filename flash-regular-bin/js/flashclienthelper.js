var FlashClientHelper = function()
{
  /**
   * Flash Client instance.
   */
  var m_FlashClient = null;

  /**
   * Whether the browser is maximized (based on the window size compared to the
   * available screen space).
   */
  var m_ResizeMaximized = false;

  /**
   * TImer that triggers the delayed resize.
   */
  var m_ResizeTimer = 0;

  /**
   * Setup the event listeners.
   * @param a_ID  The Flach Client's ID (e.g. ExternalInterface.objectID)
   */
  function attachClient(a_ID)
  {
    if (m_FlashClient != null) {
      detachClient();
    }

    m_FlashClient = document.getElementById(a_ID);
    
    if (m_FlashClient == null ||
        typeof m_FlashClient.onBeforeUnload != "function" ||
        typeof m_FlashClient.onUnload != "function") {
      m_FlashClient = null;
      return;
    }

    // we manually take away the focus from the flash object
    // so that the first click in it will trigger an activation event
    m_FlashClient.blur();

    // Register event listeners
    if (document.addEventListener) {
      window.addEventListener("beforeunload", onXBeforeUnload, false);
      window.addEventListener("unload", onXUnload, false);

      } else if (document.attachEvent) {
      window.attachEvent("onbeforeunload", onXBeforeUnload);
      window.attachEvent("onunload", onXUnload);

    } else {
      m_FlashClient = null;
    }
    
    // Workarounds for browser- or version-specific bugs
    var l_FlashVersion = undefined;
    if (swfobject) {
      l_FlashVersion = swfobject.getFlashPlayerVersion();
    }
    if (l_FlashVersion &&
        l_FlashVersion.major   ==   11 &&
        l_FlashVersion.minor   ==    3 &&
        l_FlashVersion.release >=  300 &&
        /Firefox\/1(3|4)(\.[0-9]+){2}$/i.test(navigator.userAgent)) {

      m_FlashClient.style.width = window.innerWidth;
      m_FlashClient.style.height = window.innerHeight;

      m_ResizeMaximized = window.outerWidth >= screen.availWidth &&
                          window.outerHeight >= screen.availHeight;

      window.addEventListener("resize", onQuirksResize);
    }
  }
  
  /**
   * Remove event listeners.
   */   
  function detachClient()
  {
    if (m_FlashClient == null) {
      return;
    }

    // De-register event listeners
    if (document.removeEventListener) {
      window.removeEventListener("beforeunload", onXBeforeUnload, false);
      window.removeEventListener("unload", onXUnload, false);

    } else if (document.attachEvent) {
      window.detachEvent("onbeforeunload", onXBeforeUnload);
      window.detachEvent("onunload", onXUnload);
    }

    // Reset the internal state.
    m_FlashClient = null;
  }

  /**
   * Called before the user leaves the client website.
   * @param a_Event
   */
  function onXBeforeUnload(a_Event)
  {
    var Message = m_FlashClient.onBeforeUnload();
    if (Message != null) {
      if ("returnValue" in a_Event) {
        a_Event.returnValue = Message;
      }
      return Message;
    }
    return undefined;
  }

  /**
   * Called when the user leaves the client website.
   * @param a_Event
   */
  function onXUnload(a_Event)
  {
    m_FlashClient.onUnload();
    detachClient();
  }
  
  /**
   * @param a_Event
   */
  function onQuirksFocus(a_Event)
  {
    m_FlashClient.onFocus(a_Event.type);
  }

  return {
    attachClient: attachClient,
    detachClient: detachClient
  };
}();