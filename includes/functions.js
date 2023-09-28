document.addEventListener("DOMContentLoaded", function() {
    const currentLang = document.getElementById("current-lang");
    const langDropdown = document.getElementById("lang-dropdown");
    let sourceLang = 'it'; // Default source language

    // Toggle dropdown visibility when the current language is clicked
    currentLang.addEventListener("click", function(event) {
        event.stopPropagation();
        langDropdown.style.display = langDropdown.style.display === "none" || langDropdown.style.display === "" ? "block" : "none";
    });

    // Translate text when a language in the dropdown is clicked
    langDropdown.addEventListener("click", async function(event) {
        const targetLang = event.target.getAttribute("data-lang");
        if (targetLang) {
            // Collect all text elements into an array
            const textElements = document.querySelectorAll("p, h1, h2, h3, h4, h5, h6, a, button, span");
            const textsToTranslate = Array.from(textElements).map(el => el.innerText);
            
            // Send all texts in a single API request
            const translatedTexts = await translateTexts(textsToTranslate, sourceLang, targetLang);
            
            // Update the DOM
            textElements.forEach((element, index) => {
                element.innerText = translatedTexts[index];
            });

            // Update source language
            sourceLang = targetLang;
        }
    });

    

    // Close dropdown if clicked outside of the dropdown area
    document.addEventListener("click", function(event) {
        if (!event.target.closest("#perseo-multilang-gpt")) {
            langDropdown.style.display = "none";
        }
    });

    // Function to translate text
    async function translateTexts(texts, sourceLang, targetLang) {
        const apiKey = perseo_params.api_key;
        const url = "https://www.wikiherbalist.com/wp-json/perseo/v1/translate_batch/";
        const payload = {
            texts: texts,
            source_lang: sourceLang,
            target_lang: targetLang
        };
        const config = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        };
        try {
            const response = await fetch(url, config);
            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}`);
            }
            const data = await response.json();
            return data.translated_texts; // Assuming the server returns an array with this name
        } catch (error) {
            console.error('Error:', error);
            return texts;
        }
    }
});
