git subsplit init git@github.com:peakphp/framework.git
git subsplit publish --heads="master" src/Common:git@github.com:peakphp/common.git
git subsplit publish --heads="master" src/Config:git@github.com:peakphp/config.git
git subsplit publish --heads="master" src/DebugBar:git@github.com:peakphp/debugbar.git
git subsplit publish --heads="master" src/Di:git@github.com:peakphp/di.git
git subsplit publish --heads="master" src/Pipelines:git@github.com:peakphp/pipelines.git
rm -rf .subsplit/