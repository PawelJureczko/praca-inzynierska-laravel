<template>
    <div class="fixed left-0 top-0 w-screen h-screen bg-tint z-[999]">
        <div class="spinner_wrapper">
            <div class="spinner">
                <div class="react1"></div>
                <div class="react2"></div>
                <div class="react3"></div>
                <div class="react4"></div>
                <div class="react5"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>

<style lang="scss" scoped>
.spinner_wrapper {
    .spinner {
        position: absolute;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
        width: 120px;
        height: 120px;
        text-align: center;
        font-size: 30px;

        > div {
            background-color: #fff;
            height: 100%;
            width: 8px;
            margin-right: 8px;
            display: inline-block;
            animation: meregang 1.2s infinite ease-in-out;
        }

        .react2 {
            animation-delay: -1.1s;
        }

        .react3 {
            animation-delay: -1s;
        }

        .react4 {
            animation-delay: -0.9s;
        }

        .react5 {
            animation-delay: -0.8s;
        }
    }

    @keyframes meregang {
        0%, 40%, 100% {
            transform: scaleY(0.4);
        }

        20% {
            background-color: orange;
            transform: scaleY(1);
        }
    }
}

</style>
