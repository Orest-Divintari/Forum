<template>
    <div
        class="alert alert-flash"
        :class="'alert-' + level"
        role="alert"
        v-show="show"
        v-text="body"
    >
        <button
            @click="close"
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
export default {
    props: ["message"],
    data() {
        return {
            body: "",
            show: false,
            level: "success"
        };
    },

    created() {
        if (this.message) {
            this.flash(this.message);
        }

        window.events.$on("flash", data => {
            this.flash(data);
        });
    },

    methods: {
        flash({ message, level }) {
            this.body = message;
            this.level = level;
            this.show = true;
            this.hide();
        },

        close() {
            this.body = "";
            this.show = false;
        },

        hide() {
            setTimeout(() => {
                this.show = false;
            }, 3000);
        }
    }
};
</script>

<style>
.alert-flash {
    position: fixed;
    right: 25px;
    bottom: 2px;
}
</style>
