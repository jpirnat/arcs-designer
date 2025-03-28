const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            iterations: [],
            card: {},
            affinities: [],
            speedModifiers: [],
            zoneModifiers: [],
            cardTypes: [],
            maxLengths: {},

            loading: true,
            loaded: false,
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

        this.loading = false;
        this.loaded = true;

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.iterations = data.iterations;
        this.card = data.card;
        this.affinities = data.affinities;
        this.speedModifiers = data.speedModifiers;
        this.zoneModifiers = data.zoneModifiers;
        this.cardTypes = data.cardTypes;
        this.maxLengths = data.maxLengths;
    },
    methods: {
        async save() {
            const url = new URL(window.location);

            this.loading = true;
            const response = await fetch(url.pathname, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    iterationId: this.card.iterationId,
                    name: this.card.name,
                    affinityId: this.card.affinityId,
                    cost: this.card.cost,
                    enflowable: this.card.enflowable,
                    speedModifier: this.card.speedModifier,
                    zoneModifier: this.card.zoneModifier,
                    startingLife: this.showLifeBurden ? this.card.startingLife : '',
                    burden: this.showLifeBurden ? this.card.burden : '',
                    cardType: this.card.cardType,
                    rulesText: this.card.rulesText,
                    attack: this.showAttackDefense ? this.card.attack : '',
                    defense: this.showAttackDefense ? this.card.defense : '',
                }),
            })
            .then(response => response.json());
            this.loading = false;

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
            }
        },
    },
});

app.mount('#app');
