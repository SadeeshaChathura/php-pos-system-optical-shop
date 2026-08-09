<?php
include "include/header.php";
include "include/topnavbar.php";
?>
<style>
.chat-shell {
    background: var(--erp-surface, #fff); border: 1px solid var(--erp-border, #e5e9f0);
    border-radius: var(--erp-radius, 0.6rem); box-shadow: var(--erp-shadow, 0 2px 10px rgba(30,41,59,0.06));
    display: flex; flex-direction: column; height: calc(100vh - 220px); min-height: 480px; overflow: hidden;
}
.chat-header {
    display:flex; align-items:center; gap:.75rem; padding: 1rem 1.25rem; border-bottom: 1px solid var(--erp-border, #e5e9f0);
}
.chat-header-icon {
    width:48px; height:48px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    background: rgba(0,123,255,.12); color:#007bff; font-size:1.3rem;
}
.chat-header-title { font-weight:700; color: var(--erp-text, #222); }
.chat-header-sub { font-size:.78rem; color: var(--erp-text-muted, #8a8f9a); }
.chat-header-actions { margin-left: auto; display:flex; align-items:center; gap:.5rem; }
.chat-reset-btn {
    display:inline-flex; align-items:center; gap:.4rem; background:#fff; color:#6c757d;
    border:1px solid var(--erp-border, #e5e9f0); border-radius:999px; padding:.4rem .9rem; font-size:.8rem; font-weight:600;
    cursor:pointer; transition: background .15s, color .15s, border-color .15s;
}
.chat-reset-btn:hover { background:#fdeeee; color:#dc3545; border-color:#f3c8c8; }
.chat-messages { flex:1; overflow-y:auto; padding: 1.25rem; display:flex; flex-direction:column; gap: .9rem; }
.chat-row { display:flex; gap:.65rem; max-width: 85%; }
.chat-row.user { align-self:flex-end; flex-direction:row-reverse; }
.chat-avatar {
    width:34px; height:34px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    font-size:.9rem; color:#fff;
}
.chat-avatar.bot { background:#007bff; }
.chat-avatar.user { background:#28a745; }
.chat-bubble {
    padding:.75rem 1rem; border-radius: 1rem; font-size:.9rem; line-height:1.5; white-space:pre-line;
}
.chat-row.bot .chat-bubble { background:#f1f4f8; color:#222; border-top-left-radius:.25rem; }
.chat-row.user .chat-bubble { background:#007bff; color:#fff; border-top-right-radius:.25rem; }
.chat-links { display:flex; flex-wrap:wrap; gap:.4rem; margin-top:.6rem; }
.chat-link-btn {
    display:inline-flex; align-items:center; gap:.4rem; background:#fff; color:#007bff;
    border:1px solid #cfe2ff; border-radius:999px; padding:.35rem .8rem; font-size:.8rem; font-weight:600;
    text-decoration:none; transition: background .15s;
}
.chat-link-btn:hover { background:#eef6ff; color:#0056b3; text-decoration:none; }
.chat-typing { display:flex; gap:4px; padding:.5rem 0; }
.chat-typing span {
    width:7px; height:7px; border-radius:50%; background:#b7bfca; display:inline-block;
    animation: chat-typing-bounce 1.2s infinite ease-in-out;
}
.chat-typing span:nth-child(2) { animation-delay:.15s; }
.chat-typing span:nth-child(3) { animation-delay:.3s; }
@keyframes chat-typing-bounce { 0%, 60%, 100% { transform: translateY(0); opacity:.5; } 30% { transform: translateY(-5px); opacity:1; } }
.chat-suggestions { padding: 0 1.25rem 1rem; display:flex; flex-wrap:wrap; gap:.5rem; }
.chat-suggestion-chip {
    border:1px solid var(--erp-border, #e5e9f0); background:#fff; color:#007bff; font-size:.8rem;
    padding:.4rem .85rem; border-radius:999px; cursor:pointer; transition: background .15s;
}
.chat-suggestion-chip:hover { background:#eef6ff; }
.chat-input-bar {
    border-top: 1px solid var(--erp-border, #e5e9f0); padding: .85rem 1.25rem; display:flex; gap:.6rem; align-items:center;
}
.chat-input-bar input {
    flex:1; border:1px solid var(--erp-border, #e5e9f0); border-radius: 999px; padding:.6rem 1.1rem; font-size:.9rem;
    outline:none;
}
.chat-input-bar input:focus { border-color:#007bff; }
.chat-send-btn {
    width:42px; height:42px; border-radius:50%; background:#007bff; color:#fff; border:none;
    display:flex; align-items:center; justify-content:center; flex-shrink:0; cursor:pointer;
}
.chat-send-btn:disabled { background:#b7d3ff; cursor:not-allowed; }
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3 d-flex align-items-center justify-content-between">
                        <h1 class="page-header-title d-flex align-items-center">
                            <div class="page-header-icon mr-2"><i class="fas fa-comments"></i></div>
                            <span>Quantum AI</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-2">
                <div class="chat-shell">

                    <div class="chat-header">
                        <div class="chat-header-icon"><i class="fas fa-comments"></i></div>
                        <div>
                            <div class="chat-header-title">Ask from Quantum AI</div>
                            <div class="chat-header-sub">Answers come straight from your sales &amp; stock data.</div>
                        </div>
                        <div class="chat-header-actions">
                            <button type="button" class="chat-reset-btn" id="chatResetBtn" title="Reset conversation">
                                <i class="fas fa-redo-alt"></i> Clear
                            </button>
                        </div>
                    </div>

                    <div class="chat-messages" id="chatMessages">
                        <div class="chat-row bot">
                            <div class="chat-avatar bot"><i class="fas fa-comments"></i></div>
                            <div class="chat-bubble">Hi! Ask me about sales, stock, reordering, or how to do something in the system — e.g. "top selling products this month" or "how do I create a sale". Say "go to sales report" to jump straight to a page. I only understand questions close to my examples for now.</div>
                        </div>
                    </div>

                    <div class="chat-suggestions" id="chatSuggestions">
                        <span class="chat-suggestion-chip">How many sales today?</span>
                        <span class="chat-suggestion-chip">Top selling products this month</span>
                        <span class="chat-suggestion-chip">Fast moving items</span>
                        <span class="chat-suggestion-chip">Slow moving items</span>
                        <span class="chat-suggestion-chip">Which items need reordering?</span>
                        <span class="chat-suggestion-chip">Sale summary of the month</span>
                        <span class="chat-suggestion-chip">Stock summary</span>
                        <span class="chat-suggestion-chip">Steps to create a sale</span>
                        <span class="chat-suggestion-chip">How to print a report</span>
                        <span class="chat-suggestion-chip">Go to sales report</span>
                        <span class="chat-suggestion-chip">What menus are available?</span>
                    </div>

                    <div class="chat-input-bar">
                        <input type="text" id="chatInput" placeholder="Type a question about your sales or stock..." autocomplete="off">
                        <button type="button" class="chat-send-btn" id="chatSendBtn"><i class="fas fa-paper-plane"></i></button>
                    </div>

                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var messages = document.getElementById('chatMessages');
    var input = document.getElementById('chatInput');
    var sendBtn = document.getElementById('chatSendBtn');
    var suggestions = document.getElementById('chatSuggestions');
    var resetBtn = document.getElementById('chatResetBtn');

    // Snapshot of the greeting bubble shown on first load, reused
    // whenever the conversation is reset.
    var defaultGreetingHtml = messages.innerHTML;

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    function addMessage(text, sender, links) {
        var row = document.createElement('div');
        row.className = 'chat-row ' + sender;

        var avatar = document.createElement('div');
        avatar.className = 'chat-avatar ' + sender;
        avatar.innerHTML = sender === 'user' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-comments"></i>';

        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        bubble.textContent = text;

        if (links && links.length) {
            var linkWrap = document.createElement('div');
            linkWrap.className = 'chat-links';
            links.forEach(function (link) {
                var a = document.createElement('a');
                a.href = link.url;
                a.className = 'chat-link-btn';
                a.innerHTML = '<i class="fas fa-arrow-right"></i>';
                a.appendChild(document.createTextNode(link.label));
                linkWrap.appendChild(a);
            });
            bubble.appendChild(linkWrap);
        }

        row.appendChild(avatar);
        row.appendChild(bubble);
        messages.appendChild(row);
        scrollToBottom();
        return row;
    }

    function addTyping() {
        var row = document.createElement('div');
        row.className = 'chat-row bot';
        row.id = 'chatTypingRow';

        var avatar = document.createElement('div');
        avatar.className = 'chat-avatar bot';
        avatar.innerHTML = '<i class="fas fa-comments"></i>';

        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        bubble.innerHTML = '<div class="chat-typing"><span></span><span></span><span></span></div>';

        row.appendChild(avatar);
        row.appendChild(bubble);
        messages.appendChild(row);
        scrollToBottom();
    }

    function removeTyping() {
        var row = document.getElementById('chatTypingRow');
        if (row) row.remove();
    }

    function resetChat() {
        messages.innerHTML = defaultGreetingHtml;
        input.value = '';
        sendBtn.disabled = false;
        scrollToBottom();
        input.focus();
    }

    function sendMessage(text) {
        text = (text || '').trim();
        if (!text) return;

        addMessage(text, 'user');
        input.value = '';
        sendBtn.disabled = true;

        // Show the typing indicator, then wait a tick so the browser
        // actually paints it before the fetch starts (rAF forces a
        // render before we move on).
        addTyping();
        requestAnimationFrame(function () {
            var startTime = Date.now();
            var minTypingMs = 1000; // dots stay visible at least this long

            var formData = new FormData();
            formData.append('message', text);

            fetch('<?php echo base_url(); ?>Aiagent/ChatQuery', {
                method: 'POST',
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                var elapsed = Date.now() - startTime;
                var wait = Math.max(0, minTypingMs - elapsed);
                setTimeout(function () {
                    removeTyping();
                    addMessage(data.text || "Sorry, I couldn't work that out.", 'bot', data.links);
                    sendBtn.disabled = false;
                    input.focus();
                }, wait);
            })
            .catch(function () {
                var elapsed = Date.now() - startTime;
                var wait = Math.max(0, minTypingMs - elapsed);
                setTimeout(function () {
                    removeTyping();
                    addMessage('Something went wrong reaching the server. Please try again.', 'bot');
                    sendBtn.disabled = false;
                    input.focus();
                }, wait);
            });
        });
    }

    sendBtn.addEventListener('click', function () { sendMessage(input.value); });
    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage(input.value);
    });
    suggestions.querySelectorAll('.chat-suggestion-chip').forEach(function (chip) {
        chip.addEventListener('click', function () { sendMessage(chip.textContent); });
    });
    resetBtn.addEventListener('click', resetChat);
});
</script>

<?php include "include/footerscripts.php"; ?>
<?php include "include/footer.php"; ?>