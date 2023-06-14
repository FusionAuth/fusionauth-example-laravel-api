const FusionAuth = (fusionAuthUrl) => {
    const catchError = ($container, err) => {
        console.error(err);
        $container.innerHTML = `Error: ${err.message}`;
    };

    const getMe = ($container) => {
        fetch(
            fusionAuthUrl + '/app/me',
            {
                method: 'GET',
                credentials: 'include',
            },
        ).then((response) => {
            if (!response.ok) {
                return catchError($container, new Error(`Got HTTP ${response.status}`));
            }

            response.json()
                .then(response => $container.innerHTML = JSON.stringify(response, null, 2))
                .catch(err => catchError($container, err));
        }).catch(err => catchError($container, err));
    };
    getMe(document.getElementById('profile'));

    const loadMessages = ($container) => {
        const callMessagesApi = (shouldRetry = false) => {
            fetch(
                '/api/messages',
                {
                    method: 'GET',
                    credentials: 'include',
                },
            ).then((response) => {
                if (!response.ok) {
                    if (response.status === 401) {
                        if (shouldRetry) {
                            console.log('Received 401 error, trying to refresh token...');
                            callMessagesApi(false);
                            return;
                        }

                        console.log('Received 401 error again');
                    }

                    catchError($container, new Error(`Got HTTP ${response.status}`));
                    return;
                }

                response.json()
                    .then(response => $container.innerHTML = JSON.stringify(response, null, 2))
                    .catch(err => catchError($container, err));
            });
        };

        callMessagesApi(true);
    };
    loadMessages(
        document.getElementById('api-results'),
    );
};
