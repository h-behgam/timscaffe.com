<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Cookie\CookieJar, Cookie\SetCookie, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Auth\Session;
use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Hashtag;
use Instagram\Model\Media;
use Instagram\Model\Profile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApiTest extends TestCase
{
    public function testValidApiCalls()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());
        $this->assertSame('1518284433', $profile->getId32Bit());
        $this->assertSame('robertdowneyjr', $profile->getUserName());
        $this->assertSame('Robert Downey Jr. Official', $profile->getFullName());
        $this->assertSame('@officialfootprintcoalition @coreresponse', $profile->getBiography());
        $this->assertSame(46383825, $profile->getFollowers());
        $this->assertSame(50, $profile->getFollowing());
        $this->assertSame('https://scontent-frt3-2.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-frt3-2.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX90AcTx&oh=ec27e20298c8765eccdfeb9c1b655f76&oe=5EEEC338', $profile->getProfilePicture());
        $this->assertSame('http://coreresponse.org/covid19', $profile->getExternalUrl());
        $this->assertFalse($profile->isPrivate());
        $this->assertTrue($profile->isVerified());
        $this->assertSame(453, $profile->getMediaCount());
        $this->assertTrue($profile->hasMoreMedias());
        $this->assertCount(12, $profile->getMedias());
        $this->assertSame('Photo shared by Robert Downey Jr. Official on May 12, 2020 tagging @netflix, @jefflemire, and @nxonnetflix. Image may contain: text', $profile->getMedias()[0]->getAccessibilityCaption());

        $this->assertSame(1518284433, $profile->__serialize()['id']);
        $this->assertSame('robertdowneyjr', $profile->__serialize()['userName']);
        $this->assertSame('Robert Downey Jr. Official', $profile->__serialize()['fullName']);
        $this->assertSame('@officialfootprintcoalition @coreresponse', $profile->__serialize()['biography']);
        $this->assertSame(46383825, $profile->__serialize()['followers']);
        $this->assertSame(50, $profile->__serialize()['following']);
        $this->assertSame('https://scontent-frt3-2.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-frt3-2.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX90AcTx&oh=ec27e20298c8765eccdfeb9c1b655f76&oe=5EEEC338', $profile->__serialize()['profilePicture']);
        $this->assertSame('http://coreresponse.org/covid19', $profile->__serialize()['externalUrl']);
        $this->assertFalse($profile->__serialize()['private']);
        $this->assertTrue($profile->__serialize()['verified']);
        $this->assertSame(453, $profile->__serialize()['mediaCount']);
        $this->assertTrue($profile->__serialize()['hasMoreMedias']);
        $this->assertCount(12, $profile->__serialize()['medias']);

        $profile = $api->getMoreMedias($profile);
        $media   = $profile->getMedias()[0];

        $this->assertSame(2224305748263047050, $media->getId());
        $this->assertSame('B7eUksNFA-K', $media->getShortCode());
        $this->assertSame('GraphVideo', $media->getTypeName());
        $this->assertSame(421, $media->getHeight());
        $this->assertSame(750, $media->getWidth());
        $this->assertSame('https://www.instagram.com/p/B7eUksNFA-K/', $media->getLink());
        $this->assertInstanceOf(\DateTime::class, $media->getDate());
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=725b6b887273f8402ea4d448abd456f9&oe=5EC809A6', $media->getDisplaySrc());
        $this->assertSame('#happybirthday little brother @harrycollettactor , quite the night out in #berlin #germany with #stubbins and #ladyrose @carmellaniadoofficial @dolittlemovie #press tour #trudges on... (🎥 @jimmy_rich ) #sweet #16', $media->getCaption());
        $this->assertSame(1939, $media->getComments());
        $this->assertSame(695047, $media->getLikes());
        $this->assertCount(5, $media->getThumbnails());
        $this->assertInstanceOf(\StdClass::class, $media->getLocation());
        $this->assertTrue($media->isVideo());
        $this->assertFalse($media->isIgtv());
        $this->assertSame(2726827, $media->getVideoViewCount());
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/c157.0.405.405a/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=72390bf5e7b875de6d6b7222337bb46e&oe=5EC7F96E', $media->getThumbnailSrc());

        $this->assertSame(2224305748263047050, $media->__serialize()['id']);
        $this->assertSame('GraphVideo', $media->__serialize()['typeName']);
        $this->assertSame(421, $media->__serialize()['height']);
        $this->assertSame(750, $media->__serialize()['width']);
        $this->assertSame('https://www.instagram.com/p/B7eUksNFA-K/', $media->__serialize()['link']);
        $this->assertInstanceOf(\DateTime::class, $media->__serialize()['date']);
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=725b6b887273f8402ea4d448abd456f9&oe=5EC809A6', $media->__serialize()['displaySrc']);
        $this->assertSame('#happybirthday little brother @harrycollettactor , quite the night out in #berlin #germany with #stubbins and #ladyrose @carmellaniadoofficial @dolittlemovie #press tour #trudges on... (🎥 @jimmy_rich ) #sweet #16', $media->__serialize()['caption']);
        $this->assertSame(1939, $media->__serialize()['comments']);
        $this->assertSame(695047, $media->__serialize()['likes']);
        $this->assertCount(5, $media->__serialize()['thumbnails']);
        $this->assertInstanceOf(\StdClass::class, $media->__serialize()['location']);
        $this->assertTrue($media->__serialize()['video']);
        $this->assertFalse($media->__serialize()['igtv']);
        $this->assertSame(2726827, $media->__serialize()['videoViewCount']);
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/c157.0.405.405a/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=72390bf5e7b875de6d6b7222337bb46e&oe=5EC7F96E', $media->__serialize()['thumbnailSrc']);
        $this->assertCount(9, $media->getHashtags());
        $this->assertSame('#happybirthday', $media->getHashtags()[0]);

        $api->logout('username');
    }

    public function testJsonMediasWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');
        $api->getMoreMedias($profile);

        $api->logout('username');
    }

    public function testProfileFetchWithNoContentInside()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('robertdowneyjr');

        $api->logout('username');
    }

    public function testProfileFetchWithNoValidJsonInside()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], '<script type="text/javascript">window._sharedData = {invalid};</script>'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('robertdowneyjr');

        $api->logout('username');
    }

    public function testValidStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $instagramStories = $api->getStories(123456788);

        $this->assertInstanceOf(\DateTime::class, $instagramStories->getExpiringDate());
        $this->assertInstanceOf(\StdClass::class, $instagramStories->getOwner());
        $this->assertFalse($instagramStories->isAllowedToReply());
        $this->assertTrue($instagramStories->isReshareable());

        foreach ($instagramStories->getStories() as $story) {
            $this->assertSame(2313478624923545316, $story->getId());
            $this->assertSame('GraphStoryVideo', $story->getTypeName());
            $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.12442-15/e15/100088826_248178139603068_2736940532157996068_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=ZFwA_I6gw40AX_dnybW&oh=700dca3b88fa748a1bd6e7bb570b23c4&oe=5EC918AF', $story->getDisplayUrl());
            $this->assertSame(750, $story->getWidth());
            $this->assertSame(1333, $story->getHeight());
            $this->assertSame('https://www.youtube.com/watch?v=-bhq2bxwAzg', $story->getCtaUrl());
            $this->assertInstanceOf(\DateTime::class, $story->getTakenAtDate());
            $this->assertInstanceOf(\DateTime::class, $story->getExpiringAtDate());
            $this->assertSame(14.5, $story->getVideoDuration());
            $this->assertCount(2, $story->getVideoResources());
            $this->assertCount(3, $story->getDisplayResources());
            $this->assertTrue($story->isAudio());
            $this->assertCount(1, $story->getHashtags());
            $this->assertCount(1, $story->getMentions());
            $this->assertCount(1, $story->getLocations());
        }

        $api->logout('username');
    }

    public function testHighlightsStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/highlights-folders.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/highlights-stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getProfile('statement.paris');

        $storyHighlights = $api->getStoryHighlightsFolder($profile->getId());

        $this->assertCount(8, $storyHighlights->getFolders());

        $folder = $api->getStoriesOfHighlightsFolder($storyHighlights->getFolders()[0]);

        $this->assertSame(18137590444014024, $folder->getId());
        $this->assertSame('STORY', $folder->getName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/s150x150/94263786_546583649377430_3277795491247917640_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=D6Img4muLocAX_bsIlI&oh=eeeec52698961ee00a070d3e210f532d&oe=5EF1ACCB', $folder->getCover());
        $this->assertCount(33, $folder->getStories());

        $api->logout('username');
    }

    public function testGetMoreMediasWithEndCursor()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY . '.username');
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);
        $api->login('username', 'password');
        $profile = $api->getMoreMediasWithCursor(12345567, 'endCursorVeryLongString');

        $medias = $profile->getMedias();
        $this->assertCount(12, $medias);

        $api->logout('username');
    }

    public function testGetMediaDetailed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-2.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-3.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $media = new Media();
        $media->setLink('https://www.instagram.com/p/CAnqPB-Jzcj/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertFalse($mediaDetailed->hasAudio());
        $this->assertNull($mediaDetailed->getVideoUrl());
        $this->assertCount(0, $mediaDetailed->getTaggedUsers());
        $this->assertCount(3, $mediaDetailed->getSideCarItems());
        $this->assertCount(3, $mediaDetailed->getDisplayResources());

        $media->setLink('https://www.instagram.com/p/CAnqPB-Jzcj/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertTrue($mediaDetailed->hasAudio());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/v/t50.2886-16/97784581_115199903279540_8370161409519117911_n.mp4?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=108&_nc_ohc=gOgqaQBnBEEAX9xddWo&oe=5ED69D0B&oh=edbbe40e7747f95edda907926a9e6af6', $mediaDetailed->getVideoUrl());
        $this->assertCount(2, $mediaDetailed->getTaggedUsers());
        $this->assertCount(0, $mediaDetailed->getSideCarItems());
        $this->assertCount(3, $mediaDetailed->getDisplayResources());

        $media->setLink('https://www.instagram.com/p/B-NYjoGpQqC/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertFalse($mediaDetailed->hasAudio());
        $this->assertCount(0, $mediaDetailed->getTaggedUsers());
        $this->assertCount(4, $mediaDetailed->getSideCarItems());
        $this->assertCount(3, $mediaDetailed->getDisplayResources());

        $this->assertInstanceOf(Profile::class, $mediaDetailed->getProfile());

        $api->logout('username');
    }

    public function testProfileFetchWithContentInAdditionalData()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-additional-data.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame('robertdowneyjr', $profile->getUserName());

        $api->logout('username');
    }

    public function testProfileFetchWithNoContentInAdditionalData()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-additional-data-invalid.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame('robertdowneyjr', $profile->getUserName());

        $api->logout('username');
    }

    public function testGetProfileById()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-id.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getProfileById(12345);
        $this->assertSame('robertdowneyjr', $profile->getUserName());

        $api->logout('username');
    }

    public function testGetProfileByIdWithInvalidId()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-id-invalid.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfileById(12345);

        $api->logout('username');
    }

    public function testGetMediaDetailedByShortCode()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $media = new Media();
        $media->setShortCode('CAnqPB-Jzcj');

        $mediaDetailed = $api->getMediaDetailedByShortCode($media);
        $this->assertSame(2317006284167526179, $mediaDetailed->getId());

        $api->logout('username');
    }

    public function testGetFollowersFeed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followers-feed.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followers-feed-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $followersFeed = $api->getFollowers(1234567);
        $this->assertCount(24, $followersFeed->getUsers());
        $this->assertSame(46650946, $followersFeed->getCount());
        $this->assertTrue($followersFeed->hasNextPage());
        $this->assertSame('QVFBVUJiZjcydktSUHZyMFFkbjdrU3NGN0M2bUhzWHQwRUNuMHJHNF9hWlBNSVA3aUxvRk5YSC02WlVNbXpHaGpUMUFJeFdjYVRIcVpaYXVVWEtfbDhYUw==', $followersFeed->getEndCursor());
        $this->assertCount(4, $followersFeed->__serialize());

        $userToTest = $followersFeed->getUsers()[0];

        $this->assertSame(41055093479, $userToTest->getId());
        $this->assertSame('martinasalomao0602', $userToTest->getUserName());
        $this->assertSame('Martina S', $userToTest->getFullName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s150x150/118546270_727334558122985_5873885402138414428_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=3nIm_ZGjF-cAX9tM_rk&oh=a27e90a68da855ed18d8a23d72e1629c&oe=5F76E1FF', $userToTest->getProfilePicUrl());
        $this->assertFalse($userToTest->isPrivate());
        $this->assertFalse($userToTest->isVerified());
        $this->assertFalse($userToTest->isFollowedByViewer());
        $this->assertFalse($userToTest->isRequestedByViewer());

        $this->assertCount(8, $userToTest->__serialize());

        // with endcursor
        $api->getMoreFollowers(1234567, $followersFeed->getEndCursor());

        $api->logout('username');
    }

    public function testErrorWithGetFollowersFeed()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], '{"data":{"user":""}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getFollowers(1234567);
    }

    public function testGetFollowingsFeed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followings-feed.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followings-feed-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $followersFeed = $api->getFollowings(1234567);
        $this->assertCount(24, $followersFeed->getUsers());
        $this->assertSame(55, $followersFeed->getCount());
        $this->assertTrue($followersFeed->hasNextPage());
        $this->assertSame('QVFCa2oybzk2OGxERHdEMzFPMGtsUjUzTUZ5MU9WZFo2SWFyeVkycWJucWlNb1FVV3VOY3V3NzZ2TkFSWHJ0MUJvX2ZQN2EzV29lRHVFQ0V3TE1vM05vNA==', $followersFeed->getEndCursor());
        $this->assertCount(4, $followersFeed->__serialize());

        // with endcursor
        $api->getMoreFollowings(1234567, $followersFeed->getEndCursor());

        $api->logout('username');
    }

    public function testErrorWithGetFollowingsFeed()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], '{"data":{"user":""}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getFollowings(1234567);
    }

    public function testNotFoundOnProfile()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('odazdozajdoazjdazodjzaodazjdazod');

        $api->logout('username');
    }

    public function testInternalErrorOnProfile()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(429, [], file_get_contents(__DIR__ . '/fixtures/profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('odazdozajdoazjdazodjzaodazjdazod');

        $api->logout('username');
    }


    private function generateCookiesForFollow(): FilesystemAdapter
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $cookiesJar = new CookieJar();

        // dummy cookies
        $cookie = new SetCookie();
        $cookie->setName('csrftoken');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar->setCookie($cookie);

        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY . '.username');
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        return $cachePool;
    }

    public function testErrorWithRolloutHash()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->follow(1234567);
    }

    public function testFollowAndUnfollowUser()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/follow.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/unfollow.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->follow(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unfollow(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testFollowUserWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, []),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->follow(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unfollow(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testFollowUserWithWrongResultStatus()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], '{"status": ""}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->follow(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unfollow(1234567);
        $this->assertSame('ko', $result);

        $this->assertTrue(true);
    }

    public function testLikeAndUnlikeUser()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/follow.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/unfollow.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->like(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unlike(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testLikeUnlikeWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], '{"status": ""}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->like(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unlike(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testGetLocation()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/location.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/location-medias.json')),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $location = $api->getLocation(558750588);

        $this->assertEquals(558750588, $location->getId());
        $this->assertSame('Little Choc Apothecary', $location->getName());
        $this->assertTrue($location->hasPublicPage());
        $this->assertSame(40.71192, $location->getLatitude());
        $this->assertSame(-73.95721, $location->getLongitude());
        $this->assertSame('little-choc-apothecary', $location->getSlug());
        $this->assertSame('NYC\'s first fully vegan and gluten-free creperie! ', $location->getDescription());
        $this->assertSame('http://littlechoc.nyc', $location->getWebsite());
        $this->assertSame('(718) 963-0420', $location->getPhone());
        $this->assertSame('LittleChoc', $location->getFacebookAlias());
        $this->assertCount(8, $location->getAddress());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/v/t51.2885-15/e35/c0.179.1440.1440a/s150x150/74607460_3223523367722884_955674193158361307_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=104&_nc_ohc=jU8HIsOILvoAX_HbDTh&tp=16&oh=2fa2f9f8da8a84e17bcecf934e37d673&oe=5FCFAF2A', $location->getProfilePicture());
        $this->assertEquals(6446, $location->getTotalMedia());
        $this->assertIsArray($location->getMedias());
        $this->assertTrue($location->hasMoreMedias());
        $this->assertSame('2420224628294009909', $location->getEndCursor());

        $moreMedias = $api->getMoreLocationMedias(558750588, $location->getEndCursor());
        $this->assertCount(24, $moreMedias->getMedias());

        $api->logout('username');
    }

    public function testGetLocationWithLocationNotFound()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(1234567);

        $api->logout('username');
    }

    public function testGetLocationWithInternalError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(429),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(558750588);

        $api->logout('username');
    }

    public function testGetLocationWithInvalidJson()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], 'RIEN'),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(1234567);

        $api->logout('username');
    }

    public function testGetHashtag()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/hashtag.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        /** @var Hashtag $hashtag */
        $hashtag = $api->getHashtag('paris');

        $this->assertSame(17841562894101784, $hashtag->getId());
        $this->assertSame('paris', $hashtag->getName());
        $this->assertFalse($hashtag->isTopMediaOnly());
        $this->assertFalse($hashtag->isFollowing());
        $this->assertTrue($hashtag->isAllowFollowing());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/v/t51.2885-15/e35/s150x150/143729191_707659816564323_8378880941716604054_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=1&_nc_ohc=k1fqM0dgMikAX8TI-Xu&tp=1&oh=0ce210f245a8b7789031a2a77d3b8357&oe=60406A2A', $hashtag->getProfilePicture());
        $this->assertSame(125782874, $hashtag->getMediaCount());
        $this->assertCount(34, $hashtag->getMedias());
        $this->assertTrue($hashtag->hasMoreMedias());
        $this->assertSame('QVFEYy1pYW90TGt4aWF0aExLQnM4c1JCYV9BbkhkbmZySzRKRVBrWEtCR0pJQlJES0NNUFVmbE5CUDF2eW45eVp0Mk1odG5KT3pJeHE0R2ZDd05reEItbA==', $hashtag->getEndCursor());
        $this->assertIsArray($hashtag->toArray());
        $this->assertSame(17841562894101784, $hashtag->__serialize()['id']);

        $api->logout('username');
    }

    public function testGetMoreHashtag()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/hashtag-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        /** @var Hashtag $hashtag */
        $hashtag = $api->getMoreHashtagMedias('paris', 'endcursor');

        $this->assertSame('paris', $hashtag->getName());

        $api->logout('username');
    }

    public function testGetMoreProfileById()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getMoreMediasWithProfileId(12345);
        $this->assertSame(12345, $profile->getId());

        $api->logout('username');
    }

    public function testGetMediaComments()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments-2.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $comments = $api->getMediaComments('CIvZJcurJaW');
        $this->assertCount(12, $comments->getComments());
        $this->assertTrue($comments->hasMoreComments());
        $this->assertSame('QVFCbnROamtGemh0QWdoN0Z1YVhTNWtPV3RqSXhIVkJaaEszdUlYNlozSGlUVi1nbXdtbF93VUliVFYzaWM5a2prMGJDNmlLZTR2VHcwajNOZWVWR3Z0ZA==', $comments->getEndCursor());
        $this->assertSame(45, $comments->getMediaCount());

        $comment = $comments->getComments()[0];
        $this->assertSame(17969014687354566, $comment->getId());
        $this->assertSame('Good themw', $comment->getCaption());
        $this->assertSame('26965138089', $comment->getOwner()->id);
        $this->assertInstanceOf(\DateTime::class, $comment->getDate());
        $this->assertIsArray($comment->toArray());
        $this->assertSame(17969014687354566, $comment->__serialize()['id']);

        $comments = $api->getMoreMediaComments('CIvZJcurJaW', $comments->getEndCursor());
        $this->assertCount(12, $comments->getComments());

        $comments2 = $api->getMediaCommentsById(2463298121680852630);
        $this->assertCount(12, $comments2->getComments());

        $api->logout('username');
    }
}
