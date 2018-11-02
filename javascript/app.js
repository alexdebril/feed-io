const host = 'https://feed-io-api.herokuapp.com';
const e = React.createElement;

const pleaseWait = e('p', null, "please wait, while feed-io is processing your request");

function readFeed() {
    ReactDOM.render(
      pleaseWait,
      document.getElementById('read-result')
    );
    var feed = document.getElementById('feedToRead');
    callFeedIo('feed/consume', feed.value)
        .then(function(response){
            response.json().then(function(feed) {
                const items = feed.items.map((item) => e('li', null, `${item.title}`));
                ReactDOM.render(
                  e('ul', null, items),
                  document.getElementById('read-result')
              );
            });
    });
}

function discoverFeeds() {
    ReactDOM.render(
      pleaseWait,
      document.getElementById('discovery-result')
    );
    var website = document.getElementById('website');
    callFeedIo('feed/discover', website.value)
        .then(function(response){
            response.json().then(function(urls) {
                const urlItems = urls.map((url) => e('li', null, url));
                ReactDOM.render(
                  e('ul', null, urlItems),
                  document.getElementById('discovery-result')
              );
            });
    });
}

function callFeedIo(endpoint, url) {
    return fetch(
        `${host}/${endpoint}`,
        {
            method: "POST",
            mode: "cors",
            body: `{"url": "${url}"}`
        }
    );
}
