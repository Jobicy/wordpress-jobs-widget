from pathlib import Path
import zipfile

root = Path(__file__).resolve().parent
destination = root / 'dist/jobicy-remote-jobs-widget.zip'
destination.parent.mkdir(exist_ok=True)
with zipfile.ZipFile(destination, 'w', zipfile.ZIP_DEFLATED) as archive:
    for path in sorted((root / 'jobicy-remote-jobs-widget').rglob('*')):
        if path.is_file():
            archive.write(path, path.relative_to(root))
    archive.write(root / 'LICENSE', 'jobicy-remote-jobs-widget/LICENSE')
print(destination)
