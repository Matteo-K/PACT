document.addEventListener("DOMContentLoaded", () => {
  const root = document.documentElement;
  let style = getComputedStyle(document.body);
  
  color = {
    primary: {
      var: "--primary",
      clr: style.getPropertyValue("--primary").trim(),
    },
    secondary: {
      var: "--secondary",
      clr: style.getPropertyValue("--secondary").trim(),
    },
    accent: { var: "--accent", clr: style.getPropertyValue("--accent").trim() },
    background: {
      var: "--background",
      clr: style.getPropertyValue("--background").trim(),
    },
    bloc: {
      var: "--bloc",
      clr: style.getPropertyValue("--bloc").trim(),
    },
    textDark: {
      var : "--text-dark",
      clr: style.getPropertyValue("--text-dark").trim(),
    }
  };
  
  function hexa_to_rgba(hexa_color, transparency) {
    if (!/^#([0-9A-F]{3}){1,2}$/i.test(hexa_color)) {
      throw new Error(
        "Format de couleur invalide. Utilisez un format hexadécimal comme #RRGGBB ou #RGB."
      );
    }
  
    if (hexa_color.startsWith("#")) {
      hexa_color = hexa_color.slice(1);
    }
  
    let r, g, b;
    if (hexa_color.length === 6) {
      r = parseInt(hexa_color.slice(0, 2), 16);
      g = parseInt(hexa_color.slice(2, 4), 16);
      b = parseInt(hexa_color.slice(4, 6), 16);
    } else {
      r = parseInt(hexa_color[0] + hexa_color[0], 16);
      g = parseInt(hexa_color[1] + hexa_color[1], 16);
      b = parseInt(hexa_color[2] + hexa_color[2], 16);
    }
  
    return `rgba(${r}, ${g}, ${b}, ${transparency})`;
  }
  
  function set_variante() {
    const MAX = 100;
    Object.keys(color).forEach((element) => {
      for (let index = 1; index * 5 < MAX; index++) {
        let value = index * 5;
  
        root.style.setProperty(
          color[element].var + value,
          hexa_to_rgba(color[element].clr, value / 100)
        );
      }
    });
  }
  
  set_variante();
});

