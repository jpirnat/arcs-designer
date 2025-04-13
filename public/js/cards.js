const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            isFirstLoadComplete: false,

            cards: [],
        };
    },
    async created() {
        const url = new URL(window.location);

        const response = await fetch('/data' + url.pathname, {
            credentials: 'same-origin',
        })
        .then(response => response.json());

        this.isFirstLoadComplete = true;

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.cards = data.cards;
    },
});

app.mount('#app');
