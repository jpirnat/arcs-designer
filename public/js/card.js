const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            isFirstLoadComplete: false,
            isLoading: true,

            sets: [],
            setIds: [],
            iterations: [],
            current: {},
            comparing: null,
            comments: [],
            affinities: [],
            speedModifiers: [],
            zoneModifiers: [],
            cardTypes: [],
            maxLengths: {},

            showSets: false,
            originalSetIds: [],
            original: {},
            newCommentText: '',
            hoveringIterationId: null,
        };
    },
    computed: {
        changedSetIds() {
            return this.setIds !== this.originalSetIds;
        },

        changedOriginalCost() {
            return this.current.cost !== this.original.cost;
        },
        changedOriginalName() {
            return this.current.name !== this.original.name;
        },
        changedOriginalStartingLife() {
            return this.current.startingLife !== this.original.startingLife;
        },
        changedOriginalEnflowable() {
            return this.current.enflowable !== this.original.enflowable;
        },
        changedOriginalAffinity() {
            return this.current.affinityId !== this.original.affinityId;
        },
        changedOriginalSpeedModifier() {
            return this.current.speedModifier !== this.original.speedModifier;
        },
        changedOriginalZoneModifier() {
            return this.current.zoneModifier !== this.original.zoneModifier;
        },
        changedOriginalCardType() {
            return this.current.cardType !== this.original.cardType;
        },
        changedOriginalRulesText() {
            return this.current.rulesText !== this.original.rulesText;
        },
        changedOriginalPower() {
            return this.current.power !== this.original.power;
        },
        changedOriginalHealth() {
            return this.current.health !== this.original.health;
        },
        changedOriginal() {
            return this.changedOriginalCost
                || this.changedOriginalName
                || this.changedOriginalStartingLife
                || this.changedOriginalEnflowable
                || this.changedOriginalAffinity
                || this.changedOriginalSpeedModifier
                || this.changedOriginalZoneModifier
                || this.changedOriginalCardType
                || this.changedOriginalRulesText
                || this.changedOriginalPower
                || this.changedOriginalHealth
            ;
        },

        isSaveEnabled() {
            return !this.isLoading && (this.changedSetIds || this.changedOriginal || this.newCommentText);
        },

        changedComparingCost() {
            return this.current.cost !== this.comparing.cost;
        },
        changedComparingName() {
            return this.current.name !== this.comparing.name;
        },
        changedComparingStartingLife() {
            return this.current.startingLife !== this.comparing.startingLife;
        },
        changedComparingEnflowable() {
            return this.current.enflowable !== this.comparing.enflowable;
        },
        changedComparingAffinity() {
            return this.current.affinityId !== this.comparing.affinityId;
        },
        changedComparingSpeedModifier() {
            return this.current.speedModifier !== this.comparing.speedModifier;
        },
        changedComparingZoneModifier() {
            return this.current.zoneModifier !== this.comparing.zoneModifier;
        },
        changedComparingCardType() {
            return this.current.cardType !== this.comparing.cardType;
        },
        changedComparingRulesText() {
            return this.current.rulesText !== this.comparing.rulesText;
        },
        changedComparingPower() {
            return this.current.power !== this.comparing.power;
        },
        changedComparingHealth() {
            return this.current.health !== this.comparing.health;
        },
        changedComparing() {
            return this.changedComparingCost
                || this.changedComparingName
                || this.changedComparingStartingLife
                || this.changedComparingEnflowable
                || this.changedComparingAffinity
                || this.changedComparingSpeedModifier
                || this.changedComparingZoneModifier
                || this.changedComparingCardType
                || this.changedComparingRulesText
                || this.changedComparingPower
                || this.changedComparingHealth
            ;
        },
    },
    async created() {
        const url = new URL(window.location);

        const response = await fetch('/data' + url.pathname + url.search, {
            credentials: 'same-origin',
        })
        .then(response => response.json());

        this.isFirstLoadComplete = true;
        this.isLoading = false;

        if (!response.data) {
            return;
        }

        const data = response.data;

        this.sets = data.sets;
        this.setIds = data.setIds;
        this.iterations = data.iterations;
        this.current = data.current;
        this.comparing = data.comparing;
        this.affinities = data.affinities;
        this.comments = data.comments;
        this.speedModifiers = data.speedModifiers;
        this.zoneModifiers = data.zoneModifiers;
        this.cardTypes = data.cardTypes;
        this.maxLengths = data.maxLengths;

        if (!this.setIds.length) {
            this.showSets = true;
        }

        this.originalSetIds = data.setIds;

        this.original = {
            iterationId: data.current.iterationId,
            name: data.current.name,
            affinityId: data.current.affinityId,
            cost: data.current.cost,
            enflowable: data.current.enflowable,
            speedModifier: data.current.speedModifier,
            zoneModifier: data.current.zoneModifier,
            startingLife: data.current.startingLife,
            cardType: data.current.cardType,
            rulesText: data.current.rulesText,
            power: data.current.power,
            health: data.current.health,
        };
    },
    methods: {
        toggleSets() {
            this.showSets = !this.showSets;
        },
        resetSetIds() {
            this.setIds = this.originalSetIds;
        },

        onHoverIteration(iterationId) {
            this.hoveringIterationId = iterationId;
        },
        onUnhoverIteration() {
            this.hoveringIterationId = null;
        },

        compare(iteration) {
            this.comparing = iteration;
        },

        showStartingLife(iteration) {
            return iteration.zoneModifier === 'leader';
        },
        showPowerHealth(iteration) {
            return iteration.cardType === 'creature';
        },

        async save() {
            if (!this.isSaveEnabled) {
                return;
            }

            const url = new URL(window.location);

            this.isLoading = true;
            const response = await fetch(url.pathname, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    setIds: this.setIds,
                    iterationId: this.current.iterationId,
                    name: this.current.name,
                    affinityId: this.current.affinityId,
                    cost: this.current.cost,
                    enflowable: this.current.enflowable,
                    speedModifier: this.current.speedModifier,
                    zoneModifier: this.current.zoneModifier,
                    startingLife: this.showStartingLife ? this.current.startingLife : '',
                    cardType: this.current.cardType,
                    rulesText: this.current.rulesText,
                    power: this.showPowerHealth ? this.current.power : '',
                    health: this.showPowerHealth ? this.current.health : '',
                    commentText: this.newCommentText,
                }),
            })
            .then(response => response.json());
            this.isLoading = false;

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
                return;
            }

            if (response.data) {
                window.location.assign('/cards');
            }
        },

        resetChanges() {
            this.current.name = this.original.name;
            this.current.affinityId = this.original.affinityId;
            this.current.cost = this.original.cost;
            this.current.enflowable = this.original.enflowable;
            this.current.speedModifier = this.original.speedModifier;
            this.current.zoneModifier = this.original.zoneModifier;
            this.current.startingLife = this.original.startingLife;
            this.current.cardType = this.original.cardType;
            this.current.rulesText = this.original.rulesText;
            this.current.power = this.original.power;
            this.current.health = this.original.health;
        },

        copyComparingIntoCurrent() {
            this.current.name = this.comparing.name;
            this.current.affinityId = this.comparing.affinityId;
            this.current.cost = this.comparing.cost;
            this.current.enflowable = this.comparing.enflowable;
            this.current.speedModifier = this.comparing.speedModifier;
            this.current.zoneModifier = this.comparing.zoneModifier;
            this.current.startingLife = this.comparing.startingLife;
            this.current.cardType = this.comparing.cardType;
            this.current.rulesText = this.comparing.rulesText;
            this.current.power = this.comparing.power;
            this.current.health = this.comparing.health;
        },

        async setAsCurrent() {
            if (this.comparing === null) {
                return;
            }

            this.isLoading = true;
            const response = await fetch('/cards/set-as-current', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cardId: this.current.cardId,
                    iterationId: this.comparing.iterationId,
                }),
            })
            .then(response => response.json());
            this.isLoading = false;

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
                return;
            }

            this.current.iterationId = this.comparing.iterationId;
            this.current.name = this.comparing.name;
            this.current.affinityId = this.comparing.affinityId;
            this.current.cost = this.comparing.cost;
            this.current.enflowable = this.comparing.enflowable;
            this.current.speedModifier = this.comparing.speedModifier;
            this.current.zoneModifier = this.comparing.zoneModifier;
            this.current.startingLife = this.comparing.startingLife;
            this.current.cardType = this.comparing.cardType;
            this.current.rulesText = this.comparing.rulesText;
            this.current.power = this.comparing.power;
            this.current.health = this.comparing.health;

            this.original.iterationId = this.comparing.iterationId;
            this.original.name = this.comparing.name;
            this.original.affinityId = this.comparing.affinityId;
            this.original.cost = this.comparing.cost;
            this.original.enflowable = this.comparing.enflowable;
            this.original.speedModifier = this.comparing.speedModifier;
            this.original.zoneModifier = this.comparing.zoneModifier;
            this.original.startingLife = this.comparing.startingLife;
            this.original.cardType = this.comparing.cardType;
            this.original.rulesText = this.comparing.rulesText;
            this.original.power = this.comparing.power;
            this.original.health = this.comparing.health;
        },

        async saveComment() {
            this.isLoading = true;
            const response = await fetch('/cards/add-comment', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    iterationId: this.current.iterationId,
                    commentText: this.newCommentText,
                }),
            })
            .then(response => response.json());
            this.isLoading = false;
            this.newCommentText = '';

            if (response.error) {
                const error = response.error;

                Swal.fire({
                    icon: 'error',
                    text: error.message,
                });
                return;
            }

            const data = response.data;

            this.comments.unshift(data.comment);
        },
    },
});

app.mount('#app');
