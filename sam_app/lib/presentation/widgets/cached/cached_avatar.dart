import 'dart:async';
import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:sam_app/data/cache/image_cache_service.dart';

class CachedAvatar extends StatefulWidget {
  final String? avatarHash;
  final Color avatarColor;
  final double circleRadius;
  final double imageSize;
  final IconData iconNotFound;
  final double iconSize;
  final Color iconColor;
  final String Function(String) imageUrlFromHash;
  final ImageCacheService imageCacheService;

  const CachedAvatar({
    super.key,
    required this.avatarHash,
    required this.avatarColor,
    required this.imageUrlFromHash,
    required this.imageCacheService,
    this.circleRadius = 20,
    this.imageSize = 40,
    this.iconNotFound = Icons.person,
    this.iconColor = Colors.white70,
    this.iconSize = 20,
  });

  @override
  State<CachedAvatar> createState() => _CachedAvatarState();
}

class _CachedAvatarState extends State<CachedAvatar> {
  final Map<String, Timer?> _retryTimers = {};

  void _scheduleRetry(String url) {
    if (_retryTimers[url] != null) return;

    _retryTimers[url] = Timer(const Duration(milliseconds: 200), () {
      _retryTimers[url]?.cancel();
      _retryTimers[url] = null;
      widget.imageCacheService.removeFromCache(url);
      if (mounted) setState(() {});
    });
  }

  Future<Uint8List> _loadImageBytes(String url) {
    return widget.imageCacheService.fetchImageBytes(url);
  }

  @override
  void dispose() {
    for (final timer in _retryTimers.values) {
      timer?.cancel();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (widget.avatarHash == null || widget.avatarHash!.isEmpty) {
      return CircleAvatar(
        backgroundColor: widget.avatarColor,
        radius: widget.circleRadius,
        child: Icon(
          widget.iconNotFound,
          color: widget.iconColor,
          size: widget.iconSize,
        ),
      );
    }

    final url = widget.imageUrlFromHash(widget.avatarHash!);

    return CircleAvatar(
      backgroundColor: widget.avatarColor,
      radius: widget.circleRadius,
      child: ClipOval(
        child: FutureBuilder<Uint8List>(
          future: _loadImageBytes(url),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Container(
                width: widget.imageSize,
                height: widget.imageSize,
                color: Theme.of(context).scaffoldBackgroundColor,
                child: const Center(
                  child: CircularProgressIndicator(strokeWidth: 2),
                ),
              );
            }
            if (snapshot.hasError) {
              _scheduleRetry(url);
              return Container(
                width: widget.imageSize,
                height: widget.imageSize,
                color: Theme.of(context).scaffoldBackgroundColor,
                child: const Center(
                  child: CircularProgressIndicator(strokeWidth: 2),
                ),
              );
            }
            return Image.memory(
              snapshot.data!,
              width: widget.imageSize,
              height: widget.imageSize,
              fit: BoxFit.cover,
            );
          },
        ),
      ),
    );
  }
}
