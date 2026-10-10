#!/usr/bin/env python3
"""Build an allowlisted theme artifact; never ship repository or runtime secrets."""
import hashlib
import json
import pathlib
import re
import tarfile
import argparse
import io

DIRECTORIES = ('assets', 'inc', 'languages', 'page-templates', 'template-parts')
ROOT_FILES = ('style.css', 'readme.txt', 'screenshot.png', '404.php', 'comments.php', 'footer.php', 'functions.php', 'header.php', 'index.php', 'page.php', 'search.php', 'searchform.php', 'sidebar.php', 'single.php')

def package(root, output, revision):
    root, output = pathlib.Path(root), pathlib.Path(output)
    if not re.fullmatch(r'[0-9a-f]{40}', revision):
        raise ValueError('Revision must be a full Git commit SHA.')
    paths = {root / name for name in ROOT_FILES if (root / name).is_file()}
    for directory in DIRECTORIES:
        paths.update(p for p in (root / directory).rglob('*') if p.is_file())
    if not {'functions.php', 'style.css'} <= {p.name for p in paths}:
        raise ValueError('Missing required theme files.')
    output.parent.mkdir(parents=True, exist_ok=True)
    with tarfile.open(output, 'w:gz') as archive:
        for path in sorted(paths):
            if path.name == '.gitkeep':
                continue
            if path.is_symlink() or any(part.startswith('.') for part in path.relative_to(root).parts):
                raise ValueError(f'Unexpected hidden file or symlink in theme: {path}')
            if path.name == 'functions.php' and path.parent == root:
                content = path.read_bytes().replace(b'<?php', ("<?php\ndefine( 'SAFETUNNELS_DEPLOYED_REVISION', '" + revision + "' );\n").encode(), 1)
                info = tarfile.TarInfo('functions.php')
                info.size, info.mode = len(content), 0o644
                archive.addfile(info, io.BytesIO(content))
            else:
                archive.add(path, arcname=path.relative_to(root).as_posix(), recursive=False)
        metadata = json.dumps({'revision': revision}).encode()
        info = tarfile.TarInfo('release.json')
        info.size, info.mode = len(metadata), 0o644
        archive.addfile(info, io.BytesIO(metadata))
    digest = hashlib.sha256(output.read_bytes()).hexdigest()
    output.with_suffix(output.suffix + '.sha256').write_text(digest + '\n')
    return digest

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--root', default='.')
    parser.add_argument('--output', required=True)
    parser.add_argument('--revision', required=True)
    args = parser.parse_args()
    package(args.root, args.output, args.revision)
