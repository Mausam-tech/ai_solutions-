/* ============================================================
   AI-Solutions — chatbot.js
   Rule-based floating chat widget
   ============================================================ */

(function () {
    'use strict';

    const toggle   = document.getElementById('chatbot-toggle');
    const window_  = document.getElementById('chatbot-window');
    const messages = document.getElementById('chatbot-messages');
    const input    = document.getElementById('chatbot-input');
    const sendBtn  = document.getElementById('chatbot-send');
    const iconOpen = document.getElementById('chatbot-icon-open');
    const iconClose= document.getElementById('chatbot-icon-close');

    if (!toggle || !window_) return;

    let isOpen    = false;
    let greeted   = false;

    // ─── TOGGLE ─────────────────────────────────────────────
    toggle.addEventListener('click', () => {
        isOpen = !isOpen;
        window_.classList.toggle('d-none', !isOpen);
        iconOpen.classList.toggle('d-none',  isOpen);
        iconClose.classList.toggle('d-none', !isOpen);

        if (isOpen && !greeted) {
            greeted = true;
            setTimeout(() => addMessage("Hello! 👋 I'm the AI-Solutions virtual assistant. How can I help you today?", 'bot'), 400);
        }
        if (isOpen && input) input.focus();
    });

    // ─── SEND ────────────────────────────────────────────────
    const send = () => {
        const text = (input.value || '').trim();
        if (!text) return;
        addMessage(text, 'user');
        input.value = '';
        setTimeout(() => addMessage(getResponse(text), 'bot'), 600);
    };

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') send(); });

    // ─── ADD MESSAGE ─────────────────────────────────────────
    function addMessage(text, sender) {
        const div = document.createElement('div');
        div.className = sender === 'bot' ? 'bot-message' : 'user-message';
        div.innerHTML = text; // allows links in bot responses
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    // ─── RULE-BASED RESPONSES ────────────────────────────────
    function getResponse(text) {
        const t = text.toLowerCase();
        const base = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';

        if (/\b(hello|hi|hey|good morning|good afternoon|good evening)\b/.test(t))
            return "Hello! Great to hear from you. How can AI-Solutions assist you today?";

        if (/\b(service|offer|provide|solution|what do you do|capabilities)\b/.test(t))
            return `We offer six core AI services:<br>
            • AI-Powered Virtual Assistant<br>
            • Digital Employee Experience Platform<br>
            • AI-Based Prototyping<br>
            • Intelligent Process Automation<br>
            • Data Analytics & Business Intelligence<br>
            • Industry-Specific AI Integration<br><br>
            <a href="${base}/services.php">View all services →</a>`;

        if (/\b(contact|reach|get in touch|email|phone|call|speak|talk)\b/.test(t))
            return `You can reach us at:<br>
            📧 info@ai-solutions.co.uk<br>
            📞 +44 (0)191 555 0100<br><br>
            Or use our <a href="${base}/contact.php">Contact Us form</a> and we'll get back to you within one business day.`;

        if (/\b(portfolio|project|case study|past work|client|example)\b/.test(t))
            return `We've delivered AI solutions across healthcare, finance, manufacturing, retail, education, and logistics. <a href="${base}/portfolio.php">Browse our portfolio →</a>`;

        if (/\b(testimonial|review|feedback|recommend|trust)\b/.test(t))
            return `Our clients love working with us — we have an average rating of 4.8 out of 5. <a href="${base}/testimonials.php">Read client testimonials →</a>`;

        if (/\b(article|blog|news|insight|read|learn)\b/.test(t))
            return `We publish regular articles on AI innovation, industry spotlights, and tech deep dives. <a href="${base}/articles.php">Read our latest articles →</a>`;

        if (/\b(event|webinar|conference|workshop|expo|upcoming)\b/.test(t))
            return `We have upcoming events including conferences, webinars, and exhibitions. <a href="${base}/events.php">See all events →</a>`;

        if (/\b(gallery|photo|image|picture|event photo)\b/.test(t))
            return `Check out our photo gallery from promotional events, team days, and partner meetings. <a href="${base}/gallery.php">View gallery →</a>`;

        if (/\b(location|address|where|sunderland|office|based)\b/.test(t))
            return "We're based at:<br>15 Derwent Street<br>Sunderland, SR1 2BB, UK<br><br>We also work with clients across the UK and internationally.";

        if (/\b(price|cost|quote|pricing|how much|budget|fee)\b/.test(t))
            return `Our pricing is tailored to each project's scope and requirements. Please <a href="${base}/contact.php">contact us</a> with your requirements and we'll provide a free consultation and quote.`;

        if (/\b(about|company|who are you|who is|founded|team)\b/.test(t))
            return "AI-Solutions is a Sunderland-based AI company that helps organisations transform their digital employee experience through intelligent software solutions. We specialise in practical, affordable AI implementations that deliver measurable results.";

        if (/\b(thank|thanks|cheers|great|awesome|perfect|helpful)\b/.test(t))
            return "You're welcome! Is there anything else I can help you with?";

        if (/\b(bye|goodbye|see you|ciao|ta)\b/.test(t))
            return "Thanks for chatting! If you have more questions, don't hesitate to reach out. Have a great day! 👋";

        // Default fallback
        return `Thanks for your message. For specific enquiries, the best way to reach us is through our <a href="${base}/contact.php">Contact Us form</a> and a member of our team will be in touch within one business day.`;
    }

})();
