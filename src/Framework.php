<?php

namespace nb\composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Framework extends LibraryInstaller {
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {
        parent::install($repo, $package);
        if ($this->composer->getPackage()->getType() == 'project' && $package->getInstallationSource() != 'source') {
            //remove tests dir
            $this->filesystem->removeDirectory($this->getInstallPath($package) . DIRECTORY_SEPARATOR . 'tests');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package) {

        if ('nbcx/framework' !== $package->getPrettyName()) {
            throw new \InvalidArgumentException('Unable to install this library!');
        }

        if ($this->composer->getPackage()->getType() !== 'project') {
            return parent::getInstallPath($package);
        }

        if ($this->composer->getPackage()) {
            $extra = $this->composer->getPackage()->getExtra();
            if (!empty($extra['nb-path'])) {
                return $extra['nb-path'];
            }
        }

        return 'nb';
    }

    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {
        parent::update($repo, $initial, $target);
        if ($this->composer->getPackage()->getType() == 'project' && $target->getInstallationSource() != 'source') {
            //remove tests dir
            $this->filesystem->removeDirectory($this->getInstallPath($target) . DIRECTORY_SEPARATOR . 'tests');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType) {
        return 'nb-framework' === $packageType;
    }
}