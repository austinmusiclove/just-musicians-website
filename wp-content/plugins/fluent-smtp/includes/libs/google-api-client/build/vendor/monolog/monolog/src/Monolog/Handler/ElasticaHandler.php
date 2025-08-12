<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FluentSmtpLib\Monolog\Handler;

<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
use FluentSmtpLib\Elastica\Document;
use FluentSmtpLib\Monolog\Formatter\FormatterInterface;
use FluentSmtpLib\Monolog\Formatter\ElasticaFormatter;
========
use FluentSmtpLib\Elastic\Elasticsearch\Response\Elasticsearch;
use Throwable;
use RuntimeException;
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
use FluentSmtpLib\Monolog\Logger;
use FluentSmtpLib\Monolog\Formatter\FormatterInterface;
use FluentSmtpLib\Monolog\Formatter\ElasticsearchFormatter;
use InvalidArgumentException;
use FluentSmtpLib\Elasticsearch\Common\Exceptions\RuntimeException as ElasticsearchRuntimeException;
use FluentSmtpLib\Elasticsearch\Client;
use FluentSmtpLib\Elastic\Elasticsearch\Exception\InvalidArgumentException as ElasticInvalidArgumentException;
use FluentSmtpLib\Elastic\Elasticsearch\Client as Client8;
/**
 * Elasticsearch handler
 *
 * @link https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/index.html
 *
 * Simple usage example:
 *
 *    $client = \Elasticsearch\ClientBuilder::create()
 *        ->setHosts($hosts)
 *        ->build();
 *
 *    $options = array(
 *        'index' => 'elastic_index_name',
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
 *        'type' => 'elastic_doc_type', Types have been removed in Elastica 7
 *    );
 *    $handler = new ElasticaHandler($client, $options);
========
 *        'type'  => 'elastic_doc_type',
 *    );
 *    $handler = new ElasticsearchHandler($client, $options);
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
 *    $log = new Logger('application');
 *    $log->pushHandler($handler);
 *
 * @author Avtandil Kikabidze <akalongman@gmail.com>
 */
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
class ElasticaHandler extends \FluentSmtpLib\Monolog\Handler\AbstractProcessingHandler
========
class ElasticsearchHandler extends \FluentSmtpLib\Monolog\Handler\AbstractProcessingHandler
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
{
    /**
     * @var Client|Client8
     */
    protected $client;
    /**
     * @var mixed[] Handler config options
     */
    protected $options = [];
    /**
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
     * @param Client  $client  Elastica Client object
     * @param mixed[] $options Handler configuration
     */
    public function __construct(\FluentSmtpLib\Elastica\Client $client, array $options = [], $level = \FluentSmtpLib\Monolog\Logger::DEBUG, bool $bubble = \true)
========
     * @var bool
     */
    private $needsType;
    /**
     * @param Client|Client8 $client  Elasticsearch Client object
     * @param mixed[]        $options Handler configuration
     */
    public function __construct($client, array $options = [], $level = \FluentSmtpLib\Monolog\Logger::DEBUG, bool $bubble = \true)
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
    {
        if (!$client instanceof \FluentSmtpLib\Elasticsearch\Client && !$client instanceof \FluentSmtpLib\Elastic\Elasticsearch\Client) {
            throw new \TypeError('Elasticsearch\\Client or Elastic\\Elasticsearch\\Client instance required');
        }
        parent::__construct($level, $bubble);
        $this->client = $client;
        $this->options = \array_merge([
            'index' => 'monolog',
            // Elastic index name
            'type' => '_doc',
            // Elastic document type
            'ignore_error' => \false,
        ], $options);
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
========
        if ($client instanceof \FluentSmtpLib\Elastic\Elasticsearch\Client || $client::VERSION[0] === '7') {
            $this->needsType = \false;
            // force the type to _doc for ES8/ES7
            $this->options['type'] = '_doc';
        } else {
            $this->needsType = \true;
        }
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->bulkSend([$record['formatted']]);
    }
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\FluentSmtpLib\Monolog\Formatter\FormatterInterface $formatter) : \FluentSmtpLib\Monolog\Handler\HandlerInterface
    {
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
        if ($formatter instanceof \FluentSmtpLib\Monolog\Formatter\ElasticaFormatter) {
            return parent::setFormatter($formatter);
        }
        throw new \InvalidArgumentException('ElasticaHandler is only compatible with ElasticaFormatter');
    }
    /**
========
        if ($formatter instanceof \FluentSmtpLib\Monolog\Formatter\ElasticsearchFormatter) {
            return parent::setFormatter($formatter);
        }
        throw new \InvalidArgumentException('ElasticsearchHandler is only compatible with ElasticsearchFormatter');
    }
    /**
     * Getter options
     *
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
     * @return mixed[]
     */
    public function getOptions() : array
    {
        return $this->options;
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter() : \FluentSmtpLib\Monolog\Formatter\FormatterInterface
    {
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
        return new \FluentSmtpLib\Monolog\Formatter\ElasticaFormatter($this->options['index'], $this->options['type']);
========
        return new \FluentSmtpLib\Monolog\Formatter\ElasticsearchFormatter($this->options['index'], $this->options['type']);
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
    }
    /**
     * {@inheritDoc}
     */
    public function handleBatch(array $records) : void
    {
        $documents = $this->getFormatter()->formatBatch($records);
        $this->bulkSend($documents);
    }
    /**
     * Use Elasticsearch bulk API to send list of documents
     *
<<<<<<<< HEAD:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticaHandler.php
     * @param Document[] $documents
     *
     * @throws \RuntimeException
     */
    protected function bulkSend(array $documents) : void
    {
        try {
            $this->client->addDocuments($documents);
        } catch (\FluentSmtpLib\Elastica\Exception\ExceptionInterface $e) {
========
     * @param  array[]           $records Records + _index/_type keys
     * @throws \RuntimeException
     */
    protected function bulkSend(array $records) : void
    {
        try {
            $params = ['body' => []];
            foreach ($records as $record) {
                $params['body'][] = ['index' => $this->needsType ? ['_index' => $record['_index'], '_type' => $record['_type']] : ['_index' => $record['_index']]];
                unset($record['_index'], $record['_type']);
                $params['body'][] = $record;
            }
            /** @var Elasticsearch */
            $responses = $this->client->bulk($params);
            if ($responses['errors'] === \true) {
                throw $this->createExceptionFromResponses($responses);
            }
        } catch (\Throwable $e) {
>>>>>>>> master:wp-content/plugins/fluent-smtp/includes/libs/google-api-client/build/vendor/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php
            if (!$this->options['ignore_error']) {
                throw new \RuntimeException('Error sending messages to Elasticsearch', 0, $e);
            }
        }
    }
    /**
     * Creates elasticsearch exception from responses array
     *
     * Only the first error is converted into an exception.
     *
     * @param mixed[]|Elasticsearch $responses returned by $this->client->bulk()
     */
    protected function createExceptionFromResponses($responses) : \Throwable
    {
        // @phpstan-ignore offsetAccess.nonOffsetAccessible
        foreach ($responses['items'] ?? [] as $item) {
            if (isset($item['index']['error'])) {
                return $this->createExceptionFromError($item['index']['error']);
            }
        }
        if (\class_exists(\FluentSmtpLib\Elastic\Elasticsearch\Exception\InvalidArgumentException::class)) {
            return new \FluentSmtpLib\Elastic\Elasticsearch\Exception\InvalidArgumentException('Elasticsearch failed to index one or more records.');
        }
        return new \FluentSmtpLib\Elasticsearch\Common\Exceptions\RuntimeException('Elasticsearch failed to index one or more records.');
    }
    /**
     * Creates elasticsearch exception from error array
     *
     * @param mixed[] $error
     */
    protected function createExceptionFromError(array $error) : \Throwable
    {
        $previous = isset($error['caused_by']) ? $this->createExceptionFromError($error['caused_by']) : null;
        if (\class_exists(\FluentSmtpLib\Elastic\Elasticsearch\Exception\InvalidArgumentException::class)) {
            return new \FluentSmtpLib\Elastic\Elasticsearch\Exception\InvalidArgumentException($error['type'] . ': ' . $error['reason'], 0, $previous);
        }
        return new \FluentSmtpLib\Elasticsearch\Common\Exceptions\RuntimeException($error['type'] . ': ' . $error['reason'], 0, $previous);
    }
}
