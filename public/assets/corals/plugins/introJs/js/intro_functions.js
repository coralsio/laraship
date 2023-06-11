class IntroJs {
    static run() {
        if (!IntroJs.isUrlAlreadyGuided()) {
            IntroJs.startIntro();
        }
    }

    static startIntro() {
        var intro = introJs();

        intro.setOptions({
            steps: corals.urlGuideConfig
        });

        intro.start();
        IntroJs.saveUrlToLocalStorage();
    }

    static saveUrlToLocalStorage() {
        let guidedUrl = localStorage.getItem('guided_urls');

        if (!_.isArray(guidedUrl)) {
            guidedUrl = _.split(guidedUrl, ',');
        }

        guidedUrl.push(corals.guideableUrl);

        localStorage.setItem("guided_urls", guidedUrl);
    }

    /**
     *
     * @returns {boolean}
     */
    static isUrlAlreadyGuided() {
        let guidedUrl = _.split(localStorage.getItem("guided_urls"), ',');

        return _.includes(guidedUrl, corals.guideableUrl);
    }
}

IntroJs.run();

