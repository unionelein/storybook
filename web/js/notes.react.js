var StorySection = React.createClass({
    getInitialState: function() {
        return {
            stories: []
        }
    },

    componentDidMount: function() {
        this.loadStoriesFromServer();
        setInterval(this.loadStoriesFromServer, 2000);
    },

    loadStoriesFromServer: function() {
        $.ajax({
            url: this.props.url,
            success: function (data) {
                this.setState({stories: data.stories});
            }.bind(this)
        });
    },

    render: function() {
        return (
            <div>
                <div className="stories-container">
                    <h2 className="stories-header">Stories</h2>
                    <div><i className="fa fa-plus plus-btn"></i></div>
                </div>
                <StoryList stories={this.state.stories} />
            </div>
        );
    }
});

var StoryList = React.createClass({
    render: function() {
        var storyNodes = this.props.stories.map(function(story) {
            return (
                <StoryBox username={story.storyName} avatarUri={story.avatarUri} date={story.date} key={story.id}>{story.story}</StoryBox>
            );
        });

        return (
            <section id="cd-timeline">
                {storyNodes}
            </section>
        );
    }
});

var StoryBox = React.createClass({
    render: function() {
        return (
            <div className="story-wrapper">
                <img src={this.props.avatarUri} className="story-img" alt="Cool Story!" />
                <h2 className="story-name"><a href="#">{this.props.storyName}</a></h2>
                <p>{this.props.children}</p>
                <span className="cd-date">{this.props.date}</span>
                <div className="clear"></div>
            </div>
        );
    }
});

window.StorySection = StorySection;
