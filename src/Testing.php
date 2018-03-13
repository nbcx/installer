<?php
namespace nb\composer;


use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Testing extends LibraryInstaller {

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package) {
        if ('nb/nb-testing' !== $package->getPrettyName()) {
            throw new \InvalidArgumentException('Unable to install this library!');
        }

        return parent::getInstallPath($package);
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
        parent::install($repo, $package);

        $this->copyTestDir($package);


    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {
        parent::update($repo, $initial, $target);

        $this->copyTestDir($target);

    }

    private function copyTestDir(PackageInterface $package) {
        $appDir = dirname($this->vendorDir);
        $source = $this->getInstallPath($package) . DIRECTORY_SEPARATOR . 'example';
        if (!is_file($appDir . DIRECTORY_SEPARATOR . 'phpunit.xml')) {
            $this->filesystem->copyThenRemove($source, $appDir);
        }
        else {
            $this->filesystem->removeDirectoryPhp($source);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType) {
        return 'nb-testing' === $packageType;
    }
}