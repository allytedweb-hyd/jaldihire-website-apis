<!-- Universal Spinner HTML -->
<div id="universal-spinner" class="spinner" style="display: none;">
    <div class="double-bounce1"></div>
    <div class="double-bounce2"></div>
</div>

<style>
    /* Spinner CSS */
    #universal-spinner {
        width: 40px;
        height: 40px;
        position: fixed; /* Fixed position to cover the screen */
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999; /* Ensure it's on top of other content */
    }
    .double-bounce1, .double-bounce2 {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #333; /* Customize color */
        position: absolute;
        top: 0;
        left: 0;
        animation: bounce 2.0s infinite ease-in-out;
    }
    .double-bounce2 {
        animation-delay: -1.0s;
    }
    @keyframes bounce {
        0%, 100% {
            transform: scale(0.0);
        }
        50% {
            transform: scale(1.0);
        }
    }
</style>




