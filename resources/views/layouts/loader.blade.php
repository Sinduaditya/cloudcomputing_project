<!-- Neo Brutalism Loader Component -->
<div id="neo-loader" class="neo-loader-overlay hidden">
    <div class="neo-loader">
        <div class="neo-loader-box"></div>
        <div class="neo-loader-text">{{ $text ?? 'Loading...' }}</div>
    </div>
</div>

<style>
    /* Neobrutalism Loader Styles */
    .neo-loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(245, 245, 245, 0.95);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(2px);
    }

    .neo-loader {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .neo-loader-box {
        width: 80px;
        height: 80px;
        background: var(--primary);
        border: 4px solid var(--secondary);
        border-radius: 12px;
        box-shadow: 8px 8px 0 var(--secondary);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: neoLoaderPulse 1.5s ease-in-out infinite;
    }

    .neo-loader-box::before {
        content: '';
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border: 4px solid var(--primary);
        border-radius: 16px;
        animation: neoLoaderRotate 2s linear infinite;
    }

    .neo-loader-box::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 4px;
        transform: translate(-50%, -50%);
        animation: neoLoaderBounce 1s ease-in-out infinite alternate;
    }

    .neo-loader-text {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 18px;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
        animation: neoLoaderTextPulse 1.5s ease-in-out infinite;
        white-space: nowrap;
    }

    @keyframes neoLoaderPulse {
        0%, 100% {
            transform: translate(-50%, -50%) scale(1);
            box-shadow: 8px 8px 0 var(--secondary);
        }
        50% {
            transform: translate(-50%, -50%) scale(1.1);
            box-shadow: 12px 12px 0 var(--secondary);
        }
    }

    @keyframes neoLoaderRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes neoLoaderBounce {
        0% {
            transform: translate(-50%, -50%) scale(0.8);
        }
        100% {
            transform: translate(-50%, -50%) scale(1.2);
        }
    }

    @keyframes neoLoaderTextPulse {
        0%, 100% {
            opacity: 0.7;
        }
        50% {
            opacity: 1;
        }
    }

    /* Hide loader by default */
    .neo-loader-overlay.hidden {
        display: none;
    }

    /* Alternative mini loader for buttons */
    .neo-btn-loader {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: neoSpinnerRotate 1s linear infinite;
        display: inline-block;
        margin-right: 8px;
    }

    @keyframes neoSpinnerRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    // Neo Loader Functions
    window.NeoLoader = {
        show: function(text = 'Loading...') {
            const loader = document.getElementById('neo-loader');
            const loaderText = loader.querySelector('.neo-loader-text');
            if (loaderText) {
                loaderText.textContent = text;
            }
            loader.classList.remove('hidden');
        },

        hide: function() {
            const loader = document.getElementById('neo-loader');
            loader.classList.add('hidden');
        },

        // Show loader with custom message
        showWithMessage: function(message) {
            this.show(message);
        }
    };

    // Auto-hide loader when page is fully loaded
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (window.NeoLoader) {
                window.NeoLoader.hide();
            }
        }, 500);
    });

    // Show loader on form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                if (window.NeoLoader) {
                    window.NeoLoader.show('Processing...');
                }
            });
        });

        // Show loader on AJAX requests
        if (typeof $ !== 'undefined') {
            $(document).ajaxStart(function() {
                if (window.NeoLoader) {
                    window.NeoLoader.show();
                }
            });

            $(document).ajaxStop(function() {
                if (window.NeoLoader) {
                    window.NeoLoader.hide();
                }
            });
        }
    });
</script>
