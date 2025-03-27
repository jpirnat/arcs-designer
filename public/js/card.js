const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            card: {},
            affinities: [],
            speedModifiers: [],
            zoneModifiers: [],
            cardTypes: [],
        };
    },
    computed: {
        showLifeBurden() {
            return this.card.zoneModifier === 'leader';
        },
        showAttackDefense() {
            return this.card.cardType === 'creature';
        },
    },
    async created() {
        const url = new URL(window.location);

        const response = await fetch('/data' + url.pathname, {
            credentials: 'same-origin',
        })
        .then(response => response.json());

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.affinities = data.affinities;
        this.speedModifiers = data.speedModifiers;
        this.zoneModifiers = data.zoneModifiers;
        this.cardTypes = data.cardTypes;
    },
});

app.mount('#app');
