git subsplit init git@github.com:peakphp/framework.git
git subsplit publish --heads="master" src/Blueprint:git@github.com:peakphp/blueprint.git
git subsplit publish --heads="master" src/Collection:git@github.com:peakphp/collection.git
git subsplit publish --heads="master" src/Common:git@github.com:peakphp/common.git
git subsplit publish --heads="master" src/Config:git@github.com:peakphp/config.git
git subsplit publish --heads="master" src/DebugBar:git@github.com:peakphp/debugbar.git
git subsplit publish --heads="master" src/Di:git@github.com:peakphp/di.git
git subsplit publish --heads="master" src/Pipeline:git@github.com:peakphp/pipeline.git
rm -rf .subsplit/