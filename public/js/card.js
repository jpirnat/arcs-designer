const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            loading: true,
            loaded: false,

            iterations: [],
            current: {},
            comparing: null,
            comments: [],
            affinities: [],
            speedModifiers: [],
            zoneModifiers: [],
            cardTypes: [],
            maxLengths: {},

            original: {},
            newCommentText: '',
            hoveringIterationId: null,
        };
    },
    computed: {
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
        changedOriginalBurden() {
            return this.current.burden !== this.original.burden;
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
        changedOriginalAttack() {
            return this.current.attack !== this.original.attack;
        },
        changedOriginalDefense() {
            return this.current.defense !== this.original.defense;
        },
        changedOriginal() {
            return this.changedOriginalCost
                || this.changedOriginalName
                || this.changedOriginalStartingLife
                || this.changedOriginalEnflowable
                || this.changedOriginalBurden
                || this.changedOriginalAffinity
                || this.changedOriginalSpeedModifier
                || this.changedOriginalZoneModifier
                || this.changedOriginalCardType
                || this.changedOriginalRulesText
                || this.changedOriginalAttack
                || this.changedOriginalDefense
            ;
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
        changedComparingBurden() {
            return this.current.burden !== this.comparing.burden;
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
        changedComparingAttack() {
            return this.current.attack !== this.comparing.attack;
        },
        changedComparingDefense() {
            return this.current.defense !== this.comparing.defense;
        },
        changedComparing() {
            return this.changedComparingCost
                || this.changedComparingName
                || this.changedComparingStartingLife
                || this.changedComparingEnflowable
                || this.changedComparingBurden
                || this.changedComparingAffinity
                || this.changedComparingSpeedModifier
                || this.changedComparingZoneModifier
                || this.changedComparingCardType
                || this.changedComparingRulesText
                || this.changedComparingAttack
                || this.changedComparingDefense
            ;
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
        this.current = data.current;
        this.comparing = data.comparing;
        this.affinities = data.affinities;
        this.comments = data.comments;
        this.speedModifiers = data.speedModifiers;
        this.zoneModifiers = data.zoneModifiers;
        this.cardTypes = data.cardTypes;
        this.maxLengths = data.maxLengths;

        this.original = {
            iterationId: data.current.iterationId,
            name: data.current.name,
            affinityId: data.current.affinityId,
            cost: data.current.cost,
            enflowable: data.current.enflowable,
            speedModifier: data.current.speedModifier,
            zoneModifier: data.current.zoneModifier,
            startingLife: data.current.startingLife,
            burden: data.current.burden,
            cardType: data.current.cardType,
            rulesText: data.current.rulesText,
            attack: data.current.attack,
            defense: data.current.defense,
        };
    },
    methods: {
        onHoverIteration(iterationId) {
            this.hoveringIterationId = iterationId;
        },
        onUnhoverIteration() {
            this.hoveringIterationId = null;
        },

        compare(iteration) {
            this.comparing = iteration;
        },

        showLifeBurden(iteration) {
            return iteration.zoneModifier === 'leader';
        },
        showAttackDefense(iteration) {
            return iteration.cardType === 'creature';
        },

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
                    iterationId: this.current.iterationId,
                    name: this.current.name,
                    affinityId: this.current.affinityId,
                    cost: this.current.cost,
                    enflowable: this.current.enflowable,
                    speedModifier: this.current.speedModifier,
                    zoneModifier: this.current.zoneModifier,
                    startingLife: this.showLifeBurden ? this.current.startingLife : '',
                    burden: this.showLifeBurden ? this.current.burden : '',
                    cardType: this.current.cardType,
                    rulesText: this.current.rulesText,
                    attack: this.showAttackDefense ? this.current.attack : '',
                    defense: this.showAttackDefense ? this.current.defense : '',
                    commentText: this.newCommentText,
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
            this.current.burden = this.original.burden;
            this.current.cardType = this.original.cardType;
            this.current.rulesText = this.original.rulesText;
            this.current.attack = this.original.attack;
            this.current.defense = this.original.defense;
        },

        copyComparingIntoCurrent() {
            this.current.name = this.comparing.name;
            this.current.affinityId = this.comparing.affinityId;
            this.current.cost = this.comparing.cost;
            this.current.enflowable = this.comparing.enflowable;
            this.current.speedModifier = this.comparing.speedModifier;
            this.current.zoneModifier = this.comparing.zoneModifier;
            this.current.startingLife = this.comparing.startingLife;
            this.current.burden = this.comparing.burden;
            this.current.cardType = this.comparing.cardType;
            this.current.rulesText = this.comparing.rulesText;
            this.current.attack = this.comparing.attack;
            this.current.defense = this.comparing.defense;
        },

        setComparingAsCurrent() {
        },

        async addComment() {
            
        },
    },
});

app.mount('#app');
