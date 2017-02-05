rm -rf ./deploy.zip
zip -r deploy.zip ./Build/Artifacts/ ./Build/Transpiled/ ./Build/compile_log ./Build/error_log
