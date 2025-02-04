import React, { useState } from 'react';
import PostList from './../components/PostList';
import AddPost from './../components/AddPost';

const Board = () => {
    const [refreshTrigger, setRefreshTrigger] = useState(false);

    const refreshPosts = () => {
        setRefreshTrigger(prev => !prev); // Forza il re-render di PostList
    };

    return (
        <div className="container mx-auto p-4">
            <AddPost onPostAdded={refreshPosts} />
            <PostList refreshTrigger={refreshTrigger} />
        </div>
    );
};

export default Board;
